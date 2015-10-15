<?php
/**
 * Created by PhpStorm.
 * User: nelson.daza
 * Date: 02/12/2014
 * Time: 03:01 PM
 */

	if( !isset( $headers ) )
		$headers = array( );
	if( !isset( $rows ) )
		$rows = array( );
	if( !isset( $rows_options ) )
		$rows_options = array( );
	if( !isset( $filters ) )
		$filters = array( );

	if( isset( $options ) )
		echo $options;

	if( !empty( $filters ) ) {
		$positions = array_flip($headers);
		$fheads = array( );
		foreach( $filters as $key => $value ) {
			if( isset( $positions[$key] ) )
				$key = $positions[$key];
			if( is_numeric( $key ) && isset( $rows[0][$key] ) )
				$fheads[$value] = $key;
		}
		if( !empty( $fheads ) ) {
?>
<table class="ui celled striped table small filters" style="width: auto">
<thead>
<tr>
<?php
			foreach( $fheads as $name => $index ) {
?>
	<th><?= $name ?></th>
<?php
			}
?>
</tr>
</thead>
<tbody>
<tr class="ui form">
<?php
			foreach( $fheads as $name => $index ) {
?>
	<td class="field">
		<select data-index="<?= $index ?>" class="sortable filter">
			<option value=""> - Todos - </option>
		</select>
	</td>
<?php
			}
?>
</tr>
</tbody>
</table>
<?php
		}
	}
?>
<a class="ui black button mini" style="float: right" href="<?= base_url() ?>services/export/excel"><i class="file excel outline icon"></i> Excel</a>
<div class="table-scroll">
<table class="ui celled striped table small sortable ">
<?php
	if( !empty( $headers ) ) {
?>
	<thead>
	<tr>
<?php
		if( !empty( $rows_options ) )
			echo '<th></th>';
		foreach( $headers as $header ) {
?>
			<th><?= $header ?></th>
<?php
		}
?>
	</tr>
	</thead>
<?php
	}
?>
	<tbody>
<?php
		foreach( $rows as $index => $row ) {
?>
	<tr>
<?php
			if( isset( $rows_options[$index] ) && $rows_options[$index] )
				if( is_array( $rows_options[$index] ) )
					echo '<td>' . implode(' ', $rows_options[$index] ) . '</td>';
				else
					echo '<td>' . $rows_options[$index] . '</td>';

			foreach( $row as $cell ) {
?>
		<td><?= $cell ?></td>
<?php
			}
?>
	</tr>
<?php
		}
?>
	</tbody>
</table>
</div>
<a class="ui black button mini" style="float: right" href="<?= base_url() ?>services/export/excel"><i class="file excel outline icon"></i> Excel</a>
<?php
	if( isset( $options ) )
		echo $options;
?>
<div class="clearfix"></div>
