<script type="text/javascript" charset="utf-8"> 
//<![CDATA[
var l = new Array();

<?php $i = 0; foreach ($x as $val) { ?>l[<?php echo $i++; ?>]='<?php echo $val; ?>';<?php } ?>

for (var i = l.length-1; i >= 0; i = i-1)
{
    if (l[i].substring(0, 1) == '|') { document.write("&#"+unescape(l[i].substring(1))+";"); }
    else { document.write(unescape(l[i])); }
}

//]]>
</script>