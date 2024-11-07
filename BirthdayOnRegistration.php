<?php
if (!defined('SMF'))
    die('Hacking attempt...');

function modify_registration_fields($regOptions)
{
    // Add a birthday field to the registration form
    $regOptions['fields']['birthday'] = array(
        'type' => 'text',
        'label' => 'Birthday (YYYY-MM-DD)',
        'required' => false,
        'validation' => 'validate_birthday',
    );
}

function validate_birthday($birthday)
{
    // Validate the birthday format
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthday)) {
        return 'Invalid birthday format. Please use YYYY-MM-DD.';
    }
    return true;
}

function save_birthday($member_id, $birthday)
{
    // Save the birthday to the database
    $birthday = htmlspecialchars($birthday, ENT_QUOTES);
    $db = database();
    $db->query('', '
        UPDATE {db_prefix}members
        SET birthday = {string:birthday}
        WHERE id_member = {int:member_id}',
        array(
            'birthday' => $birthday,
            'member_id' => $member_id,
        )
    );
}

function register_hooks()
{
    // Hook into the registration process
    add_hook('register', 'modify_registration_fields');
    add_hook('register_save', 'save_birthday');
}

register_hooks();

?>
