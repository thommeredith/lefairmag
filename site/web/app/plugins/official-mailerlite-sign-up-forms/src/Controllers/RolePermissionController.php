<?php
namespace MailerLiteForms\Controllers;

class RolePermissionController
{
    public $roles;

    public $rolesWithNames;

    public $settings;

    public $allowedRoles;

    public $allowedPermissions;

    const PERMISSIONS = [
        'delete_forms',
        'edit_forms',
    ];

    const ALLOWED_ROLES = [
      'editor',
      'contributor',
      'author'
    ];
    const FORM_FIELDS = [
        'form_name' => 'Form Name',
        'form_title' => 'Form Title',
        'form_description' => 'Form Description',
        'success_message' => 'Success Message',
        'button_name' => 'Button Name',
        'please_wait' => 'Please Wait Text',
        'language' => 'Language',
        'form_fields' => 'Form Fields',
        'groups' => 'Groups',
        'email_label' => 'Email Field Label',
        'email_placeholder' => 'Email Field Placeholder',
    ];

    private $loggedInUser;

    public $isEnabled;

    public function __construct()
    {
        global $wp_roles;
        global $current_user;
        $roles = isset($wp_roles) ? $wp_roles->get_names() : [];
        foreach($roles as $key => $value) {
           if(!in_array($key, self::ALLOWED_ROLES)) {
               unset($roles[$key]);
           }
        }
        $this->roles = array_keys($roles);
        $this->rolesWithNames = $roles;
        $this->settings = get_option('mailerlite_forms_user_role_settings', []);
        $this->loggedInUser = $current_user;

        if(empty($this->settings)) {
            update_option('mailerlite_forms_user_role_settings', [
                'enabled' => false,
                'allowed_roles' => ['editor'],
                'allowed_permissions' => []
            ]);
            $this->settings = get_option('mailerlite_forms_user_role_settings');
        }
        $this->isEnabled = $this->settings['enabled'];
        $this->allowedRoles = $this->settings['allowed_roles'];
        $this->allowedPermissions = $this->settings['allowed_permissions'];
    }

    public function canEdit(string $field):bool
    {
        if($this->isAdmin()) {
            return true;
        }
        return $this->isAllowed() && in_array($field, $this->allowedPermissions);
    }

    public function canDelete():bool
    {
        if($this->isAdmin()) {
            return true;
        }
        return $this->isAllowed() && in_array('delete_forms', $this->allowedPermissions);
    }

    public static function instance()
    {
        return new self;
    }

    public function isAdmin()
    {
        return in_array('administrator', $this->loggedInUser->roles);
    }

    public function isAllowed()
    {
        if($this->isAdmin()) {
            return true;
        }
        if(!$this->isEnabled) {
            return false;
        }
        $allowed = false;
        foreach($this->loggedInUser->roles as $role)
        {
            if(in_array($role, $this->allowedRoles))
            {
                $allowed = true;
                break;
            }
        }
        return $allowed;
    }

    public function toggleRolesAndPermissions()
    {
        $this->settings['enabled'] = !$this->isEnabled;
        update_option('mailerlite_forms_user_role_settings', $this->settings);
    }

    public function editAllowedRolesAndPermissions($roles = [], $permissions = [])
    {
        $roles[] = 'editor';
        $this->settings['allowed_roles'] = $roles;
        $this->settings['allowed_permissions'] = $permissions;
        update_option('mailerlite_forms_user_role_settings', $this->settings);
    }

}