<?php
/**
 * Created by PhpStorm.
 * User: nelson.daza
 * Date: 28/11/2014
 * Time: 04:05 PM
 */

	if( !isset( $attributes ) )
		$attributes = array();
	if( !isset( $attributes['name'] ) )
		$attributes['name'] = '';
	if( !isset( $attributes['value'] ) )
		$attributes['value'] = '';
	if( !isset( $attributes['type'] ) )
		$attributes['type'] = 'text';

	$readonly = ( isset( $attributes['readonly'] ) && $attributes['readonly'] );
	if( isset( $attributes['readonly'] ) && !$attributes['readonly'] )
		unset( $attributes['readonly'] );

	if( $attributes['type'] != 'hidden' || ( isset( $field ) && !$field ) ) {
?>
<div class="field <?= ( isset( $error ) && $error ? 'error' : '' ) ?>">
<?
	}

	if(  isset( $label ) && $label ) {
?>
		<label class="control-label" for="<?= ( isset( $attributes['id'] ) && $attributes['id'] ? $attributes['id'] : '' ) ?>"><?= $label ?></label>
<?
	}

	switch( $attributes['type'] ) {
		case 'hidden':
			echo form_hidden( $attributes['name'], $attributes['value'] );
			break;
		case 'textarea':
			if( $readonly ) {
				echo form_hidden( $attributes['name'], $attributes['value'] );
?>
			<div class="ui small form segment">
				<?= $attributes['value'] ?>
			</div>
<?
			}
			else
				echo form_textarea( $attributes['name'], $attributes['value'] );
			break;
		case 'multiselect':
			echo form_multiselect( $attributes['name'], ( isset( $options ) ? $options : array() ),  ( isset( $selected ) ? $selected : array() ) );
			break;
		case 'multicheckbox':
			$options = ( isset( $options ) ? $options : array() );
?>
			<div class="grouped fields">
<?
				foreach( $options as $option ) {
?>
				<div class="field">
<?
					if ( $readonly ) {
?>
					<span class="ui teal mini label"><?= $option['label'] ?></span>
<?
					}
					else {
?>
					<div class="ui toggle checkbox">
						<input type="checkbox" name="<?= $option['name'] ?>" value="<?= $option['value'] ?>" <?= ( isset( $option['checked'] ) && $option['checked'] ? 'checked="checked"' : '' ) ?>>
						<label><?= $option['label'] ?></label>
					</div>
<?
					}
?>
				</div>
<?
				}
?>
			</div>
<?
			break;
		case 'dropdown':
			echo form_dropdown( $attributes['name'], ( isset( $options ) ? $options : array() ),  ( isset( $selected ) ? $selected : array() ),' id="' . $attributes['id'] . '" class="chosen-select"' );
			break;
		case 'checkbox':
		case 'radio':
			echo form_checkbox( $attributes, $attributes['value'], ( isset( $checked ) && $checked ) );
			break;
		case 'submit':
			echo form_submit( $attributes, $attributes['value'] );
			break;
		case 'reset':
			echo form_reset( $attributes, $attributes['value'] );
			break;
		case 'button':
			echo form_button( $attributes, $attributes['value'] );
			break;
		case 'item_image':
?>
			<div class="ui items">
				<div class="item">
					<div class="ui tiny image"><?= $image ?></div>
					<div class="content">
<?
			if ( isset( $header ) && $header ) {
?>
						<div class="header"><?= $header ?></div>
<?
			}
?>
						<div class="description field">
<?
			if ( !isset( $image ) || !$image )
				echo form_upload( $attributes );
			else
				echo( isset( $description ) && $description ? $description : '' );
?>
						</div>
					</div>
				</div>
			</div>
<?
			break;
		case 'datetime':
			if( $readonly ) {
				echo form_hidden( $attributes['name'], $attributes['value'] );
?>
			<div class="ui small form segment">
				<?= $attributes['value'] ?>
			</div>
<?
			}
			else {
				$attributes['type'] = 'text';
				echo form_input( $attributes, $attributes['value'], 'readonly="readonly"' );
?>
				<script type="text/javascript">
					$('#<?= $attributes['name'] ?>').datetimepicker({
						lang:'es',
						format:'Y-m-d H:i',
						allowTimes:[
							'9:00', '10:00', '11:00',
							'19:00', '20:00', '21:00'
						]
						//mask:true,
						// '9999/19/39 29:59' - digit is the maximum possible for a cell
					});
				</script>
<?
			}
			break;
		default:
			if( $readonly ) {
				echo form_hidden( $attributes['name'], $attributes['value'] );
?>
			<div class="ui small form segment">
				<?= $attributes['value'] ?>
			</div>
<?
			}
			else
				echo form_input( $attributes );
	}

	if( $attributes['type'] != 'hidden' || ( isset( $field ) && !$field ) ) {
?>
</div>
<?
	}
	if( !isset( $divider ) || $divider ) {
?>
<div class="ui fitted divider"></div>
<?
	}
