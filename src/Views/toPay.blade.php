
<form method="post" action="{{$response->getUrl()}}" id="payForm">
    <input type="hidden" name="token_ws" value="{{$response->getToken()}}" />
    <input type="submit" value="" hidden />
</form>
<script type="text/javascript">
    document.getElementById("payForm").submit();
</script>
