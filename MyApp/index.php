<?php
?>
<!DOCTYPE>
<HTML>
<head>
</head>
<body>
<script>
document.write("attempting to establish connection...<br>");

document.write(location.port);

var conn = new WebSocket('ws://52.204.229.101:8080');
document.write("did something...<br>");
conn.onopen = function(e) {
    console.log("Connection established!");
    document.write("Connection successful<br>");
};

conn.onmessage = function(e) {
    console.log(e.data);
    document.write(e.data);
};
document.write("\nDone!");
</script>
</body>
</HTML>
