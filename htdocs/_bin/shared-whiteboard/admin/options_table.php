<table class="form-table">
<?php
foreach($this->options_def as $key => $option)
{
?>
  <tr>
    <th scope="row"><label for="<?php echo $key; ?>"><?php echo $option['name']; ?></label></th>
    <td>
    <?php
    switch($option['type'])
    {
      // render regular text input
      case 'text':
        $v = isset($option['force_value'])?$option['force_value']:self::htmlent($this->getOption($key));
        echo '<input type="text"'.(isset($option['class'])?' class="'.$option['class'].'"':'').' id="'.$key.'" name="'.$key.'" value="'.$v.'" />';

        if ($key == 'singleBoardId')
          echo '<input type="button" onclick="document.getElementById(\''.$key.'\').value = Math.floor(4294967296 + Math.random() * 4294967296).toString(16);" class="button button-secondary" value="'.esc_attr__('Generate', self::ld).'" />';
        break;

      // render select box
      case 'select':
        $val = isset($option['force_value'])?$option['force_value']:$this->getOption($key);

        echo '<select name="'.$key.'" id="'.$key.'"'.(isset($option['class'])?' class="'.$option['class'].'"':'').'>';
        foreach($option['values'] as $k => $v)
          echo '<option value="'.$k.'"'.($val == $k?' selected':'').'>'.$v.'</option>';
        echo '</select>';

        break;
    }

    if (isset($option['title']))
      echo '<br /><i>'.$option['title'].'</i>';

    if (isset($option['helper']))
      echo '<br /><i>'.$option['helper'].'</i>';
    ?>
    </td>
  </tr>
<?php
}
?>
</table>
