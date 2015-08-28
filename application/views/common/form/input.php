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

	if( !isset( $attributes['id'] ) )
		$attributes['id'] = $attributes['name'];

	if( isset($attributes['group']) && $attributes['group'] ) {
?>
	<h4 class="ui top attached header"><?= $attributes['group'] ?></h4>
	<div class="ui secondary attached segment" id="<?= $attributes['id'] ?>_holder">
<?php
	}
	if( $GLOBALS['actualCol'] == 1 ) {
		$size = 'grouped';
		$cols = ( isset( $cols ) && (int)$cols > 0 ? (int)$cols : 0 );

		switch( $cols ) {
			case 2:
				$size = 'two';
				break;
			case 3:
				$size = 'three';
				break;
			case 4:
				$size = 'four';
				break;
			case 5:
				$size = 'five';
				break;
			case 6:
				$size = 'six';
				break;
			case 7:
				$size = 'seven';
				break;
			default:
				$siZe = '';
		}
?>
		<div class="<?= $size ?> fields">
<?php
	}
	if( $attributes['type'] != 'hidden' || ( isset( $field ) && !$field ) ) {
?>
			<div class="field <?= ( isset( $error ) && $error ? 'error' : '' ) ?>">
<?php
	}

	if(  isset( $label ) && $label ) {
		$forAtt = ( $readonly || $attributes['type'] == 'multicheckbox' ? '' : 'for="' . ( isset( $attributes['id'] ) && $attributes['id'] ? $attributes['id'] : '' ) . '"' );
?>
				<label class="control-label" <?= $forAtt ?> ><?= $label ?></label>
<?php
	}

	switch( $attributes['type'] ) {
		case 'hidden':
			echo form_hidden( $attributes['name'], $attributes['value'], $attributes['id'] );
			break;
		case 'textarea':
			if( $readonly ) {
				echo form_hidden( $attributes['name'], $attributes['value'], $attributes['id'] );
?>
				<div class="ui small form segment read-only"><?= htmlentities( $attributes['placeholder'] ? $attributes['placeholder'] : $attributes['value'] ) ?></div>
<?php
			}
			else {
				unset($attributes['type']);
				echo form_textarea($attributes);
			}
			break;
		case 'multiselect':
			echo form_multiselect( $attributes['name'], ( isset( $options ) ? $options : array() ),  ( isset( $selected ) ? $selected : array() ) );
			break;
		case 'multicheckbox':
			$options = ( isset( $options ) ? $options : array() );
			if( !isset( $selected ) )
				$selected = array( );

			$type = 'grouped';
			$attributes['cols'] = ( !isset( $attributes['cols'] ) ? 1 : (int)$attributes['cols'] );

			switch( $attributes['cols'] ) {
				case 2:
					$type = 'two';
					break;
				case 3:
					$type = 'three';
					break;
				case 4:
					$type = 'four';
					break;
			}
?>
				<div class="<?= $type ?> fields">
<?php
				foreach( $options as $co => $option ) {
					if ( $readonly ) {
						if ( in_array( $option['value'], $selected ) ) {
?>
					<div class="field">
						<span class="ui label"><?= $option['label'] ?></span>
					</div>
<?php
						}
					}
					else {
						$idTMP = $attributes['id'] . '_' . $co . '_' . rand(1000000,9999999);
?>
					<div class="field">
						<div class="ui toggle checkbox">
							<input type="checkbox" name="<?= $attributes['name'] ?>[]" id="<?= $idTMP ?>" value="<?= $option['value'] ?>" <?= ( ( isset( $option['checked'] ) && $option['checked'] ) || in_array( $option['value'], $selected ) ? 'checked="checked"' : '' ) ?>>
							<label for="<?= $idTMP ?>"><?= $option['label'] ?></label>
						</div>
					</div>
<?php
					}
				}
?>
				</div>
<?php
			break;
		case 'dropdown':
			if( $readonly ) {
	echo form_hidden( $attributes['name'], $attributes['value'], $attributes['id'] );
?>
				<div class="ui small form segment read-only">
					<?= htmlentities( $attributes['placeholder'] ? $attributes['placeholder'] : $attributes['value'] ) ?>
				</div>
<?php
			}
			else
				echo form_dropdown( $attributes['name'], ( isset( $options ) ? $options : array() ),  ( isset( $selected ) ? $selected : array() ),' id="' . $attributes['id'] . '" class="chosen-select"' );
			break;
		case 'checkbox':
					if ( $readonly ) {
?>
				<div class="field">
					<span class="ui label"><?= htmlentities( $attributes['placeholder'] ? $attributes['placeholder'] : $attributes['value'] ) ?></span>
				</div>
<?php
					}
					else {
?>
				<div class="field">
					<div class="ui toggle checkbox">
						<input type="checkbox" id="<?= $attributes['id'] ?>" name="<?= $attributes['name'] ?>" value="<?= $attributes['value'] ?>" <?= ( ( isset( $attributes['checked'] ) && $attributes['checked'] ) ? 'checked="checked"' : '' ) ?>>
						<label for="<?= $attributes['id'] ?>"><?= ( $attributes['placeholder'] ? $attributes['placeholder'] : $attributes['value'] ) ?></label>
					</div>
				</div>
<?php
					}
			break;
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
				echo form_hidden( $attributes['name'], $attributes['value'], $attributes['id'] );
?>
				<div class="ui small form segment read-only">
					<?= htmlentities( $attributes['placeholder'] ? $attributes['placeholder'] : $attributes['value'] ) ?>
				</div>
<?php
			}
			else {
				$attributes['type'] = 'text';
				echo form_input( $attributes, $attributes['value'], 'readonly="readonly"' );
?>
				<script type="text/javascript">
					$('#<?= $attributes['id'] ?>').datetimepicker({
						lang:'es',
						format:'Y-m-d H:i'
					});
				</script>
<?php
			}
			break;
		case 'date':
			if( $readonly ) {
				echo form_hidden( $attributes['name'], $attributes['value'], $attributes['id'] );
?>
				<div class="ui small form segment read-only">
					<?= htmlentities( $attributes['placeholder'] ? $attributes['placeholder'] : $attributes['value'] ) ?>
				</div>
<?php
			}
			else {
				$attributes['type'] = 'text';
				echo form_input( $attributes, $attributes['value'], 'readonly="readonly"' );
?>
				<script type="text/javascript">
					$('#<?= $attributes['id'] ?>').datetimepicker({
						lang:'es',
						format:'Y-m-d',
						timepicker: false
					});
				</script>
<?php
			}
			break;
		default:
			if( $readonly ) {
				echo form_hidden( $attributes['name'], $attributes['value'], $attributes['id'] );
?>
				<div class="ui small form segment read-only">
					<?= htmlentities( $attributes['placeholder'] ? $attributes['placeholder'] : $attributes['value'] ) ?>
				</div>
<?php
			}
			else
				echo form_input( $attributes );
	}

	if( !isset( $attributes['type'] ) || $attributes['type'] != 'hidden' || ( isset( $field ) && !$field ) ) {
?>
			</div><!-- 3 -->
<?php
	}
	if( $GLOBALS['actualCol'] == 0 && isset( $cols ) && $cols > 0 ) {
?>
		</div><!-- 2 -->
<?php
	}
	if( isset($attributes['group-end']) && $attributes['group-end'] ) {
?>
	</div><!-- 1 -->
<?php
	}
	else if( !isset( $divider ) || $divider ) {
?>
	<div class="ui fitted divider"></div>
<?php
	}
