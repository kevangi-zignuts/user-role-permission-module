<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
  <form action="{{ route('email.forget.password') }}" method="post" class="w-75 mx-auto p-5 m-5 ">
    @csrf
    <div class="form-group">
      <label for="exampleInputEmail1 p-3">Email address</label>
      <input type="email" class="form-control m-3" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" name="email">
    </div>
    <button type="submit" class="btn btn-primary m-3">Send Email</button>
  </form>
</body>
</html>
