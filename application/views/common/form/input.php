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

	if( !isset( $GLOBALS['actualCol'] ) )
		$GLOBALS['actualCol'] = 0;

	if( isset( $cols ) && $cols > 0 ) {
		$GLOBALS['actualCol'] ++;
		if( $GLOBALS['actualCol'] < $cols ) {
			$divider = false;
		}
		else {
			$GLOBALS['actualCol'] = 0;
			$divider = true;
		}
	}

	$readonly = ( isset( $attributes['readonly'] ) && $attributes['readonly'] );
	if( isset( $attributes['readonly'] ) && !$attributes['readonly'] )
		unset( $attributes['readonly'] );

	if( $GLOBALS['actualCol'] == 1 ) {
?>
<div class="two fields">
<?php
	}
	if( $attributes['type'] != 'hidden' || ( isset( $field ) && !$field ) ) {
?>
<div class="field <?= ( isset( $error ) && $error ? 'error' : '' ) ?>">
<?php
	}

	if(  isset( $label ) && $label ) {
?>
		<label class="control-label" for="<?= ( isset( $attributes['id'] ) && $attributes['id'] ? $attributes['id'] : '' ) ?>"><?= $label ?></label>
<?php
	}

	switch( $attributes['type'] ) {
		case 'hidden':
			echo form_hidden( $attributes['name'], $attributes['value'] );
			break;
		case 'textarea':
			if( $readonly ) {
				echo form_hidden( $attributes['name'], $attributes['value'] );
?>
			<div class="ui small form segment read-only">
				<?= $attributes['value'] ?>
			</div>
<?php
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
<?php
				foreach( $options as $option ) {
?>
				<div class="field">
<?php
					if ( $readonly ) {
?>
					<span class="ui teal mini label"><?= $option['label'] ?></span>
<?php
					}
					else {
?>
					<div class="ui toggle checkbox">
						<input type="checkbox" name="<?= $option['name'] ?>" value="<?= $option['value'] ?>" <?= ( isset( $option['checked'] ) && $option['checked'] ? 'checked="checked"' : '' ) ?>>
						<label><?= $option['label'] ?></label>
					</div>
<?php
					}
?>
				</div>
<?php
				}
?>
			</div>
<?php
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
<?php
			if ( isset( $header ) && $header ) {
?>
						<div class="header"><?= $header ?></div>
<?php
			}
?>
						<div class="description field">
<?php
			if ( !isset( $image ) || !$image )
				echo form_upload( $attributes );
			else
				echo( isset( $description ) && $description ? $description : '' );
?>
						</div>
					</div>
				</div>
			</div>
<?php
			break;
		case 'datetime':
			if( $readonly ) {
				echo form_hidden( $attributes['name'], $attributes['value'] );
?>
			<div class="ui small form segment read-only">
				<?= $attributes['value'] ?>
			</div>
<?php
			}
			else {
				$attributes['type'] = 'text';
				echo form_input( $attributes, $attributes['value'], 'readonly="readonly"' );
?>
				<script type="text/javascript">
					$('#<?= $attributes['name'] ?>').datetimepicker({
						lang:'es',
						format:'Y-m-d H:i',
					});
				</script>
<?php
			}
			break;
		case 'date':
			if( $readonly ) {
				echo form_hidden( $attributes['name'], $attributes['value'] );
?>
			<div class="ui small form segment read-only">
				<?= $attributes['value'] ?>
			</div>
<?php
			}
			else {
				$attributes['type'] = 'text';
				echo form_input( $attributes, $attributes['value'], 'readonly="readonly"' );
?>
				<script type="text/javascript">
					$('#<?= $attributes['name'] ?>').datetimepicker({
						lang:'es',
						format:'Y-m-d',
						allowTimes:[]
					});
				</script>
<?php
			}
			break;
		default:
			if( $readonly ) {
				echo form_hidden( $attributes['name'], $attributes['value'] );
?>
			<div class="ui small form segment read-only">
				<?= $attributes['value'] ?>
			</div>
<?php
			}
			else
				echo form_input( $attributes );
	}

	if( $attributes['type'] != 'hidden' || ( isset( $field ) && !$field ) ) {
?>
</div>
<?php
	}
	if( $GLOBALS['actualCol'] == 0 && $cols > 0 ) {
?>
</div>
<?php
	}
	if( !isset( $divider ) || $divider ) {
?>
<div class="ui fitted divider"></div>
<?php
	}
