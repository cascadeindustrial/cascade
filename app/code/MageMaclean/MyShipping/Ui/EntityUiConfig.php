<?php

declare(strict_types=1);

namespace MageMaclean\MyShipping\Ui;

class EntityUiConfig
{
    /**
     * @var string
     */
    private $module;
    /**
     * @var string
     */
    private $entityName;
    /**
     * @var string
     */
    private $interface;
    /**
     * @var string
     */
    private $namespace;
    /**
     * @var array
     */
    private $data;

    /**
     * EntityUiConfig constructor.
     * @param string $interface
     * @param array $data
     */
    public function __construct(
        string $interface,
        array $data = []
    ) {
        $this->interface = $interface;
        $this->data = $data;
        $this->parseInterfaceName();
    }

    /**
     * @return string
     */
    public function getInterface(): string
    {
        return $this->interface;
    }

    /**
     * parse interface name to determine the module name and entity name
     * interface should look like `[Namespace]\[Module]\Api\Data\[EntityName]Interface`
     */
    private function parseInterfaceName()
    {
        if (!$this->module || !$this->namespace || !$this->entityName) {
            $interface = ltrim($this->interface, '\\');
            $parts = explode('\\', $interface);
            if (count($parts) < 5) {
                throw new \InvalidArgumentException("Interface name does not comply with the Magento standards");
            }
            $basename = $parts[count($parts) - 1];
            if (substr($basename, -strlen('Interface')) !== 'Interface') {
                throw new \InvalidArgumentException("Interface name must end with the string 'Interface'");
            }
            $this->namespace = $parts[0];
            $this->module = $parts[1];
            $entityName = substr($basename, 0, strlen($basename) - strlen('Interface'));
            $this->entityName = strtolower(
                trim(
                    preg_replace(
                        '/([A-Z]|[0-9]+)/',
                        "_$1",
                        $entityName
                    ),
                    '_'
                )
            );
        }
    }

    /**
     * @return string
     */
    public function getBackLabel(): string
    {
        $label = $this->data['labels']['back'] ?? '';
        return ($label) ? __($label)->render() : __('Back')->render();
    }

    /**
     * @return string
     */
    public function getSaveLabel(): string
    {
        $label = $this->data['labels']['save'] ?? '';
        return ($label) ? __($label)->render() : __('Save')->render();
    }

    /**
     * @return string
     */
    public function getSaveAndDuplicateLabel(): string
    {
        $label = $this->data['labels']['save_and_duplicate'] ?? '';
        return ($label) ? __($label)->render() : __('Save & Duplicate')->render();
    }

    /**
     * @return string
     */
    public function getSaveAndCloseLabel(): string
    {
        $label = $this->data['labels']['save_and_close'] ?? '';
        return ($label) ? __($label)->render() : __('Save & close')->render();
    }

    /**
     * @return bool
     */
    public function getAllowSaveAndClose(): bool
    {
        return isset($this->data['save']['allow_close']) ? (bool)$this->data['save']['allow_close'] : true;
    }

    /**
     * @return bool
     */
    public function getAllowSaveAndDuplicate(): bool
    {
        return isset($this->data['save']['allow_duplicate']) ? (bool)$this->data['save']['allow_duplicate'] : true;
    }

    /**
     * @return string
     */
    public function getSaveFormTarget(): string
    {
        $target = $this->data['save_form_target'] ?? '';
        if ($target) {
            return $target;
        }
        $target = strtolower($this->module) . '_' . $this->entityName . '_form';
        return $target . '.' . $target;
    }

    /**
     * @return string
     */
    public function getDeleteLabel(): string
    {
        $label = $this->data['labels']['delete'] ?? '';
        return ($label) ? __($label)->render() : __('Delete')->render();
    }

    /**
     * @return string
     */
    public function getDeleteMessage(): string
    {
        $label = $this->data['labels']['delete_message'] ?? '';
        return ($label) ? __($label)->render() : __('Are you sure you want to delete the item?')->render();
    }

    /**
     * @return string
     */
    public function getDeletePopupTitle(): string
    {
        $label = $this->data['labels']['delete_title'] ?? '';
        return ($label)
            ? __($label, $this->getNameAttribute())->render()
            : __('Delete "${ $.$data.%1 }"', $this->getNameAttribute())->render();
    }

    /**
     * @return mixed|string
     */
    public function getRequestParamName(): string
    {
        $param = $this->data['request_param'] ?? '';
        return ($param) ? $param : $this->entityName . '_id';
    }

    /**
     * @return string
     */
    public function getListPageTitle(): string
    {
        $label = $this->data['list']['page_title'] ?? '';
        return ($label)
            ? __($label)->render()
            : __(ucwords(str_replace('_', ' ', $this->entityName)))->render();
    }

    /**
     * @return string
     */
    public function getMenuItem(): string
    {
        $menu = $this->data['menu'] ?? null;
        if ($menu === null) {
            $this->data['menu'] = $this->namespace . '_' . $this->module . '::' .
                strtolower($this->module) . '_' . $this->entityName;
        }
        return $this->data['menu'];
    }

    /**
     * @return string
     */
    public function getDeleteSuccessMessage(): string
    {
        $message = $this->data['messages']['delete']['success'] ?? '';
        return ($message) ? __($message)->render() : __('Item was deleted successfully')->render();
    }

    /**
     * @return string
     */
    public function getDeleteMissingEntityMessage(): string
    {
        $message = $this->data['messages']['delete']['missing_entity'] ?? '';
        return ($message) ? __($message)->render() : __('Item for delete was not found')->render();
    }

    /**
     * @return string
     */
    public function getGeneralDeleteErrorMessage(): string
    {
        $message = $this->data['messages']['delete']['error'] ?? '';
        return ($message) ? __($message)->render() : __('There was a problem deleting the item.')->render();
    }

    /**
     * @return string
     */
    public function getSaveSuccessMessage(): string
    {
        $message = $this->data['messages']['save']['success'] ?? '';
        return ($message) ? __($message)->render() : __('Item was saved successfully.')->render();
    }

    /**
     * @return string
     */
    public function getSaveErrorMessage(): string
    {
        $message = $this->data['messages']['save']['error'] ?? '';
        return ($message) ? __($message)->render() : __('There was a problem saving the item.')->render();
    }

    /**
     * @return string
     */
    public function getDuplicateSuccessMessage(): string
    {
        $message = $this->data['messages']['save']['duplicate'] ?? '';
        return ($message) ? __($message)->render() : __('Item was duplicated successfully.')->render();
    }

    /**
     * @param $count
     * @return string
     */
    public function getMassDeleteSuccessMessage($count)
    {
        $message = $this->data['messages']['mass_delete']['success'] ?? '';
        return ($message)
        ? __($message, $count)->render()
        : __('%1 items were successfully deleted', $count)->render();
    }

    /**
     * @return string
     */
    public function getMassDeleteErrorMessage(): string
    {
        $message = $this->data['messages']['mass_delete']['error'] ?? '';
        return ($message) ? __($message)->render() : __('There was a problem deleting the items')->render();
    }

    /**
     * @return string
     */
    public function getNewLabel(): string
    {
        $label = $this->data['labels']['new'] ?? '';
        return ($label) ? __($label)->render() : __('Add new item')->render();
    }

    /**
     * @return string
     */
    public function getNameAttribute(): string
    {
        return $this->data['name_attribute'] ?? 'title';
    }

    /**
     * @return string
     */
    public function getPersistoryKey(): string
    {
        return $this->data['persistor_key'] ?? $this->entityName;
    }

    /**
     * @return string
     */
    public function getEditUrlPath()
    {
        $url = $this->data['edit_url'] ?? '';
        return ($url)
            ? $url
            : strtolower($this->module) . '/' . strtolower(str_replace('_', '', $this->entityName)) . '/edit';
    }

    /**
     * @return string
     */
    public function getDeleteUrlPath()
    {
        $url = $this->data['delete_url'] ?? '';
        return ($url)
            ? $url
            : strtolower($this->module) . '/' . strtolower(str_replace('_', '', $this->entityName)) . '/delete';
    }
}
