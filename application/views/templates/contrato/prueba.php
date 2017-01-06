<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <script type="text/javascript" src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    <script type="text/javascript">
        tinymce.init({
            selector: '#contrato',
        });
    </script>
</head>
<body>
    <?php echo form_open('path/to/controller/update/method'); ?>

<table cellpadding="6" cellspacing="1" style="width:100%" border="0">

<tr>
        <th>QTY</th>
        <th>huerto</th>
        <th>Item Description</th>
        <th style="text-align:right">Item Price</th>
        <th style="text-align:right">Sub-Total</th>
</tr>
<pre>
<?php $i = 1; ?>

<?php foreach ($this->cart->contents() as $items): ?>

       <?php //var_dump($items);?>

<?php endforeach; ?>
</pre>
<tr>
        <td colspan="2"> </td>
        <td class="right"><strong>Total</strong></td>
        <td class="right">$<?php echo $this->cart->format_number($this->cart->total()); ?></td>
</tr>

</table>

<p><?php echo form_submit('', 'Update your Cart'); ?></p>
    <textarea id="contrato"><?= $output ?></textarea>
</body>
</html>