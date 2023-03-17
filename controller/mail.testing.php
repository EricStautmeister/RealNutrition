<html>

<head>
    <title>Email Validation</title>
</head>

<body>
    <p>Click this Button to Authenticate your Email</p>
    <form action='http://localhost:8000/email' method='post'>
        <input type='email' value=$mail hidden>
        <input type='password' value=$pwd hidden>
        <input type='submit' value='Click here'>
    </form>
</body>

</html>