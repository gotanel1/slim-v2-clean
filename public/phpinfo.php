<?php
echo "<h1>✅ PHP is Working!</h1>";
echo "<p><strong>PHP Version:</strong> " . PHP_VERSION . "</p>";
echo "<p><strong>Request URI:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p><strong>Script Name:</strong> " . $_SERVER['SCRIPT_NAME'] . "</p>";
echo "<p><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</p>";

echo "<hr>";
echo "<h2>Test API:</h2>";
echo "<ul>";
echo "<li><a href='/service/api/health'>/service/api/health</a></li>";
echo "<li><a href='/service/api/auth/test'>/service/api/auth/test</a></li>";
echo "<li><a href='/service/test.html'>/service/test.html</a></li>";
echo "</ul>";
