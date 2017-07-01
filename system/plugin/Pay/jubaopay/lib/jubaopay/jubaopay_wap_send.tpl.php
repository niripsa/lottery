<html>
<head>
<title>To Jubaopay Page</title>
</head>
<body>
<h3>正在跳转到聚宝支付....</h3>
<form name='jubaopay' action='http://www.jubaopay.com/apiwapsyt.htm' method='post'>
<input type="hidden" name="message" value="<?php echo $message; ?>"/>
<input type="hidden" name="signature" value="<?php echo $signature; ?>"/>
<input type="hidden" name="payMethod" value="<?php echo $payMethod; ?>"/>
<input type="hidden" name="tab" value="<?php echo $tab; ?>"/>
</form>
<script>document.forms['jubaopay'].submit();</script>
</body>
</html>