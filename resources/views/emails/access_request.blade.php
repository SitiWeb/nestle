<!DOCTYPE html>
<html>
<head>
    <title>Access Request</title>
</head>
<body>
    <h1>Access Request</h1>
    <p>A new access request has been submitted. Here are the details:</p>
    <ul>
        <li><strong>Name:</strong> {{ $validatedData['name'] }}</li>
        <li><strong>Title:</strong> {{ $validatedData['title'] }}</li>
        <li><strong>Position:</strong> {{ $validatedData['position'] }}</li>
        <li><strong>Office Location:</strong> {{ $validatedData['office'] }}</li>
        <li><strong>Email:</strong> {{ $validatedData['email'] }}</li>
        <li><strong>Desired password:</strong> {{ $validatedData['password'] }}</li>
    </ul>
    <p>Please review the request and take appropriate action.</p>
</body>
</html>