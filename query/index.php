<?php
include "connect.php";
?>
<html>
<head>
	<link rel="stylesheet" media="all" href="style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
</head>
<body>
	<div id="columns">
<?php
		$sql = "SELECT column_name FROM information_schema.columns WHERE table_name='lift_data'";
		foreach($myPDO->query($sql) as $column) {
			$id = $column[0];
?>
			<div class="column" draggable="true"><header><?php echo $column[0]; ?></header><input type="checkbox" name="<?php echo $id; ?>" value="<?php echo $id; ?>" checked></div>
<?php
		}
?>
	</div> <!-- #columns -->
	<button id="submit">Submit</button>
	<form action="export.php" method="POST" style="display: none;">
		<input type="text" name="columns">
		<input type="submit">
	</form>
	<div id="results">
	</div>
<script>

var dragSrcEl = null;

function handleDragStart(e) {
  // Target (this) element is the source node.
  this.style.opacity = '0.4';

  dragSrcEl = this;

  e.dataTransfer.effectAllowed = 'move';
  e.dataTransfer.setData('text/html', this.innerHTML);
}

function handleDragOver(e) {
  if (e.preventDefault) {
    e.preventDefault(); // Necessary. Allows us to drop.
  }

  e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.

  return false;
}

function handleDragEnter(e) {
  // this / e.target is the current hover target.
  this.classList.add('over');
}

function handleDragLeave(e) {
  this.classList.remove('over');  // this / e.target is previous target element.
}

function handleDrop(e) {
  // this/e.target is current target element.

  if (e.stopPropagation) {
    e.stopPropagation(); // Stops some browsers from redirecting.
  }

  // Don't do anything if dropping the same column we're dragging.
  if (dragSrcEl != this) {
    // Set the source column's HTML to the HTML of the column we dropped on.
    dragSrcEl.innerHTML = this.innerHTML;
    this.innerHTML = e.dataTransfer.getData('text/html');
  }

  return false;
}

function handleDragEnd(e) {
  // this/e.target is the source node.
  this.style.opacity = '1';
  
  [].forEach.call(cols, function (col) {
    col.classList.remove('over');
  });
}

var cols = document.querySelectorAll('#columns .column');
[].forEach.call(cols, function(col) {
  col.addEventListener('dragstart', handleDragStart, false);
  col.addEventListener('dragenter', handleDragEnter, false)
  col.addEventListener('dragover', handleDragOver, false);
  col.addEventListener('dragleave', handleDragLeave, false);
  col.addEventListener('drop', handleDrop, false);
  col.addEventListener('dragend', handleDragEnd, false);
});

var checkedData = "";
$("#submit").click(function() {
	$("input:checked").each(function () {
		var value = $(this).val();
		checkedData += value + ', ';
	});
	/*$.post("export.php", { columns: checkedData})
		.done(function(data) {
			//$("#results").html(data);
			location.href='export.php';
		});*/
	$("input[name='columns'").val(checkedData);
	$("form").submit();
	checkedData = "";
});

</script>
</body>
</html>