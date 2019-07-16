<style>
.destaque{background:#CCCCCC;}
</style>
<script type="application/javascript">
$(function(){
$('table#curso tbody tr').hover(
    function(){
        $(this).addClass('destaque');
    },
    function(){
        $(this).removeClass('destaque');
    }
    );
});
</script>