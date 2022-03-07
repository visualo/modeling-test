<!DOCTYPE html>
<html>

<head>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css">

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap-theme.min.css">

</head>
<style>
.content {
  max-width: 500px;
  margin: auto;
}
</style>
<body>
<div class="jumbotron text-center">
  <h1>Modeling Test</h1>
  <p>Parse request-data.json into the query similar to result.sql</p>
</div>

	<div class="content">
		<p>Click on the "Choose File" button to upload a json file:</p>

		<form method="post" action="upload.php" method="post" enctype="multipart/form-data" class="form-horizontal" role="form" align="center">
		  <input type="file" name="jsonUpload" id="jsonUpload">			
		  <input type="submit" style="float:right" name="submit">
		</form>
	</div>

</body>
</html>