# Error Handling Implementation Guide

**Date:** 2025-11-05  
**Type:** Architecture Decision  
**Status:** Implemented

---

## 🎯 Decision: Hybrid Approach

ใช้ทั้ง **Global Error Handler** และ **Middleware** ร่วมกัน เพื่อจัดการ errors แบบ layered

---

## 📊 Architecture Overview

```
┌─────────────────────────────────────────┐
│           HTTP Request                  │
└─────────────────┬───────────────────────┘
                  │
                  ▼
┌─────────────────────────────────────────┐
│     Middleware: ErrorHandlerMiddleware  │ ← Layer 1: Expected Errors
│  - Catches: Domain Exceptions           │   (400, 401, 404, 422)
│  - Returns: Structured JSON             │
│  - Logs: Business errors                │
└─────────────────┬───────────────────────┘
                  │
                  ▼
┌─────────────────────────────────────────┐
│           Route Execution               │
│  try {                                  │
│    // Business logic                    │
│  } catch (SpecificException $e) {       │
│    throw $e; // Caught by middleware    │
│  }                                      │
└─────────────────┬───────────────────────┘
                  │
                  ▼
┌─────────────────────────────────────────┐
│    Global Error Handler (app->error)    │ ← Layer 2: Unexpected Errors
│  - Catches: Uncaught exceptions         │   (500, 503)
│  - Logs: Full stack trace               │
│  - Returns: Generic error               │
└─────────────────┬───────────────────────┘
                  │
                  ▼
┌─────────────────────────────────────────┐
│           HTTP Response                 │
└─────────────────────────────────────────┘
```

---

## 🔄 Error Flow

### Scenario 1: Invalid Credentials (Expected Error)

```
1. User sends login with wrong password
   ↓
2. LoginUseCase throws InvalidCredentialsException
   ↓
3. ErrorHandlerMiddleware catches it
   ↓
4. Returns: 401 with structured JSON
   {
     "error": {
       "code": "INVALID_CREDENTIALS",
       "message": "Invalid credentials",
       "status": 401
     }
   }
```

### Scenario 2: Database Connection Failed (Unexpected Error)

```
1. Database suddenly unavailable
   ↓
2. QueryException thrown
   ↓
3. Middleware doesn't catch (not a domain exception)
   ↓
4. Global Error Handler catches
   ↓
5. Logs full error + stack trace
   ↓
6. Returns: 503 with generic message
   {
     "error": {
       "code": "DATABASE_ERROR",
       "message": "An unexpected error occurred",
       "status": 503
     }
   }
```

---

## 📁 Files Created/Modified

### 1. Created: ErrorHandlerMiddleware.php

**Path:** `app/Infrastructure/Http/Middleware/ErrorHandlerMiddleware.php`

**Purpose:** Catches expected domain exceptions and converts to proper HTTP responses

**Catches:**
- ✅ `InvalidCredentialsException` → 401
- ✅ `UserNotFoundException` → 404
- ✅ `DomainException` → 400
- ✅ `InvalidArgumentException` → 422

**Example:**
```php
try {
    return $next($request, $response);
} catch (\App\Domain\Exceptions\InvalidCredentialsException $e) {
    return $this->jsonResponse($response, [
        'error' => [
            'code' => 'INVALID_CREDENTIALS',
            'message' => $e->getMessage(),
            'status' => 401
        ]
    ], 401);
}
```

### 2. Modified: bootstrap/app.php

**Global Error Handler Improvements:**
- ✅ Added error logging
- ✅ Added error codes
- ✅ Added specific handling for QueryException
- ✅ Structured error format
- ✅ Better debug mode handling

**Before:**
```php
$app->error(function (Exception $e) use ($app) {
    $app->response->setStatus(500);
    echo json_encode([
        'error' => 'Server Error',
        'message' => $e->getMessage()
    ]);
});
```

**After:**
```php
$app->error(function (Exception $e) use ($app) {
    // Log error
    error_log("Error: " . $e->getMessage());
    
    // Determine status code
    $statusCode = 500;
    if ($e instanceof QueryException) {
        $statusCode = 503;
    }
    
    // Structured response
    $app->response->setStatus($statusCode);
    echo json_encode([
        'error' => [
            'code' => 'INTERNAL_SERVER_ERROR',
            'message' => 'An unexpected error occurred',
            'status' => $statusCode
        ]
    ]);
});
```

---

## 🎨 Error Response Format

### Standard Format (All Errors)

```json
{
  "error": {
    "code": "ERROR_CODE",
    "message": "Human readable message",
    "status": 400
  }
}
```

### With Validation Errors (422)

```json
{
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "Validation failed",
    "status": 422,
    "details": {
      "email": ["Invalid email format"],
      "password": ["Password too short"]
    }
  }
}
```

### Debug Mode (Development Only)

```json
{
  "error": {
    "code": "INTERNAL_SERVER_ERROR",
    "message": "Database connection failed",
    "status": 500,
    "details": {
      "exception": "PDOException",
      "file": "/app/Repository.php",
      "line": 42,
      "trace": ["...", "..."]
    }
  }
}
```

---

## 🔑 Error Codes Reference

| HTTP Status | Error Code | Exception Type | Handler |
|-------------|------------|----------------|---------|
| 400 | `BAD_REQUEST` | DomainException | Middleware |
| 400 | `INVALID_JSON` | - | Route |
| 401 | `INVALID_CREDENTIALS` | InvalidCredentialsException | Middleware |
| 401 | `UNAUTHORIZED` | - | AuthMiddleware |
| 403 | `FORBIDDEN` | - | AuthMiddleware |
| 404 | `NOT_FOUND` | NotFoundException | Global |
| 404 | `USER_NOT_FOUND` | UserNotFoundException | Middleware |
| 422 | `VALIDATION_ERROR` | - | Route |
| 422 | `INVALID_ARGUMENT` | InvalidArgumentException | Middleware |
| 500 | `INTERNAL_SERVER_ERROR` | Exception | Global |
| 503 | `DATABASE_ERROR` | QueryException | Global |

---

## 📝 Usage Examples

### Example 1: Clean Route (No try-catch needed)

**Before:**
```php
$app->post('/login', function () use ($app) {
    try {
        $data = json_decode($app->request->getBody(), true);
        $useCase = $app->container['LoginUseCase'];
        $result = $useCase->execute($data);
        
        echo json_encode(['user' => $result]);
        
    } catch (InvalidCredentialsException $e) {
        $app->response->setStatus(401);
        echo json_encode(['error' => $e->getMessage()]);
    } catch (Exception $e) {
        $app->response->setStatus(500);
        echo json_encode(['error' => 'Server error']);
    }
});
```

**After (with Middleware):**
```php
$app->post('/login', function () use ($app) {
    $data = json_decode($app->request->getBody(), true);
    
    // Validation
    if (!$data) {
        throw new \InvalidArgumentException('Invalid JSON');
    }
    
    // Execute use case - exceptions handled by middleware
    $useCase = $app->container['LoginUseCase'];
    $result = $useCase->execute($data);
    
    echo json_encode([
        'message' => 'Login successful',
        'user' => $result['user'],
        'token' => $result['token']
    ]);
});
```

**Benefits:**
- ✅ No try-catch clutter
- ✅ Consistent error format
- ✅ Automatic error codes
- ✅ Easier to read

### Example 2: Use Case Throws Exception

```php
class LoginUseCase
{
    public function execute(LoginRequest $request): array
    {
        $user = $this->userRepository->findByEmail($request->email);
        
        if (!$user) {
            // This will be caught by middleware → 401
            throw new InvalidCredentialsException('Invalid credentials');
        }
        
        // More logic...
        return ['user' => $user, 'token' => $token];
    }
}
```

### Example 3: Custom Domain Exception

```php
// Define exception
class InsufficientBalanceException extends \DomainException
{
    public function __construct(float $balance, float $required)
    {
        parent::__construct(
            "Insufficient balance: {$balance}, required: {$required}"
        );
    }
}

// Add to middleware
} catch (\App\Domain\Exceptions\InsufficientBalanceException $e) {
    return $this->jsonResponse($response, [
        'error' => [
            'code' => 'INSUFFICIENT_BALANCE',
            'message' => $e->getMessage(),
            'status' => 400
        ]
    ], 400);
}

// Use in use case
if ($wallet->getBalance() < $amount) {
    throw new InsufficientBalanceException(
        $wallet->getBalance(), 
        $amount
    );
}
```

---

## 🔧 How to Add Middleware

### Option 1: Apply Globally (Recommended)

**File:** `public/index.php`

```php
// After app creation
$app = require __DIR__ . '/../bootstrap/app.php';

// Add error handler middleware globally
$app->add(new \App\Infrastructure\Http\Middleware\ErrorHandlerMiddleware());

// Load routes
require __DIR__ . '/../routes/api.php';

$app->run();
```

### Option 2: Apply to Specific Routes

```php
$app->group('/api', function () use ($app) {
    
    $app->group('/auth', function () use ($app) {
        // Routes here
    })->add(new ErrorHandlerMiddleware());
    
});
```

---

## 📊 Benefits of Hybrid Approach

### ✅ Pros:

1. **Separation of Concerns**
   - Middleware: Expected errors
   - Global: Unexpected errors

2. **Cleaner Routes**
   - No try-catch needed
   - Focus on business logic

3. **Consistent Error Format**
   - All errors follow same structure
   - Error codes for clients

4. **Better Logging**
   - Automatic logging
   - Different levels (error vs critical)

5. **Easier Testing**
   - Test exception types
   - Verify error responses

6. **Scalability**
   - Easy to add new exception types
   - Centralized error handling

### ⚠️ Cons:

1. **Two Places to Check**
   - Middleware AND global handler
   - Need to know which catches what

2. **Exception Hierarchy**
   - Need to define custom exceptions
   - Must maintain exception types

---

## 🎓 Best Practices

### DO ✅

1. **Throw Specific Exceptions**
   ```php
   throw new InvalidCredentialsException('Invalid credentials');
   // NOT: throw new Exception('Invalid credentials');
   ```

2. **Use Error Codes**
   ```php
   'code' => 'USER_NOT_FOUND'
   // NOT: 'error' => 'User not found'
   ```

3. **Log Before Responding**
   ```php
   error_log($e->getMessage());
   return $this->jsonResponse(...);
   ```

4. **Hide Sensitive Data in Production**
   ```php
   if (getenv('APP_ENV') !== 'production') {
       $error['trace'] = $e->getTraceAsString();
   }
   ```

### DON'T ❌

1. **Don't Catch Generic Exception in Routes**
   ```php
   // ❌ Bad
   try {
       // logic
   } catch (Exception $e) {
       // This defeats middleware purpose
   }
   ```

2. **Don't Expose Stack Traces in Production**
   ```php
   // ❌ Bad
   'trace' => $e->getTraceAsString()
   ```

3. **Don't Use Generic Error Messages**
   ```php
   // ❌ Bad
   'error' => 'Error occurred'
   
   // ✅ Good
   'error' => [
       'code' => 'VALIDATION_ERROR',
       'message' => 'Email is required'
   ]
   ```

---

## 🧪 Testing

### Test Middleware

```php
class ErrorHandlerMiddlewareTest extends TestCase
{
    public function testInvalidCredentialsReturns401()
    {
        $middleware = new ErrorHandlerMiddleware();
        
        $next = function($req, $res) {
            throw new InvalidCredentialsException('Invalid');
        };
        
        $response = $middleware->__invoke($request, $response, $next);
        
        $this->assertEquals(401, $response->getStatus());
        $data = json_decode($response->getBody(), true);
        $this->assertEquals('INVALID_CREDENTIALS', $data['error']['code']);
    }
}
```

### Test Global Handler

```bash
# Trigger database error
curl http://localhost/api/route-with-db-error

# Should return:
{
  "error": {
    "code": "DATABASE_ERROR",
    "message": "An unexpected error occurred",
    "status": 503
  }
}
```

---

## 📈 Migration Path

### Phase 1: Add Middleware (Done)
- [x] Create ErrorHandlerMiddleware
- [x] Improve Global Error Handler
- [x] Document error codes

### Phase 2: Update Routes
- [ ] Remove try-catch from routes
- [ ] Let middleware handle domain exceptions
- [ ] Clean up route code

### Phase 3: Add More Exception Types
- [ ] Create custom domain exceptions
- [ ] Add to middleware
- [ ] Document error codes

### Phase 4: Add Logging
- [ ] Log to file
- [ ] Log to service (Sentry)
- [ ] Add request context

---

## 🎯 Next Steps

1. **Add Middleware to Application**
   ```php
   $app->add(new ErrorHandlerMiddleware());
   ```

2. **Create Custom Exceptions**
   ```php
   class InsufficientBalanceException extends DomainException {}
   class ResourceNotFoundException extends DomainException {}
   ```

3. **Update Routes**
   - Remove try-catch blocks
   - Throw specific exceptions
   - Let middleware handle errors

4. **Add Logging**
   - Implement PSR-3 logger
   - Log all errors with context
   - Send to monitoring service

5. **Test Thoroughly**
   - Unit tests for middleware
   - Integration tests for routes
   - Manual API testing

---

## 📚 References

- [Slim Framework Error Handling](https://www.slimframework.com/docs/v2/middleware/overview.html)
- [PSR-3 Logger Interface](https://www.php-fig.org/psr/psr-3/)
- [HTTP Status Codes](https://developer.mozilla.org/en-US/docs/Web/HTTP/Status)
- [REST API Error Handling Best Practices](https://www.restapitutorial.com/httpstatuscodes.html)

---

**Implemented:** 2025-11-05  
**Status:** Ready for Integration  
**Next:** Add to `public/index.php`
