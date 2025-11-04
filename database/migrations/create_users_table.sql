-- SQL Server 2008 Compatible
-- Create users table

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='users' AND xtype='U')
CREATE TABLE users (
    id INT IDENTITY(1,1) PRIMARY KEY,
    email NVARCHAR(255) NOT NULL,
    password NVARCHAR(255) NOT NULL,
    name NVARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT GETDATE(),
    updated_at DATETIME DEFAULT GETDATE(),
    CONSTRAINT UQ_users_email UNIQUE (email)
);
GO

-- Trigger for auto-update updated_at
IF NOT EXISTS (SELECT * FROM sys.triggers WHERE name = 'trg_users_updated_at')
BEGIN
    EXEC('
        CREATE TRIGGER trg_users_updated_at
        ON users
        AFTER UPDATE
        AS
        BEGIN
            SET NOCOUNT ON;
            UPDATE users
            SET updated_at = GETDATE()
            FROM users u
            INNER JOIN inserted i ON u.id = i.id
        END
    ')
END
GO
