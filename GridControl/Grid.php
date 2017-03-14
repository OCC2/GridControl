<?php
namespace OCC2\control;

/**
 * GridException
 * @author Milan Onderka
 * @category Exceptions
 * @package occ2
 * @version 1.0.0
 */
class GridException extends \Exception{}

/**
 * Grid control
 * extension of Ublaboo/Datagrid
 * load schema from model config
 * @author Milan Onderka
 * @category Controls
 * @package occ2
 * @version 1.0.0
 */
class GridControl extends \Nette\Application\UI\Control{
    /**
     * datasource
     * @var \BaseModule\IDBModel
     */
    public $source;
    
    /**
     * schema of  datagrid
     * @var array
     */
    public $schema=[];
    
    /**
     * filter select options container
     * @var array
     */
    public $filterSelectOptions=[];
    
    /**
     * column status optiond container
     * @var array
     */
    public $collumnStatusOptions=[];
    
    /**
     * group action options container
     * @var array
     */
    public $groupActionOptions=[];
    
    /**
     * @var \Kdyby\Translation\Translator
     */
    public $translator;

    /**
     * constructor
     * @param type $source
     * @return void
     */
    public function __construct(\BaseModule\IDBModel $source, \Kdyby\Translation\Translator $translator=null) {
        $this->source = $source;
        $config = $this->source->getConfig();
        isset($config["gridSchema"]) ? $this->schema=$config["gridSchema"] : false;
        $translator!=null ? $this->setTranslator($translator) : false;
        return;
    }
    
    /**
     * @param \Kdyby\Translation\Translator $translator
     * @return void
     */
    public function setTranslator(\Kdyby\Translation\Translator $translator) {
        $this->translator = $translator;
        return;
    }
    
    /**
     * render control
     * @return void
     */
    public function render(){
        $this->template->render(__DIR__ . '/grid.latte');
        return;
    }
    
    /**
     * set filter select options
     * @param string $collumn
     * @param array $options
     * @return void
     */
    public function setFilterSelectOptions($collumn,$options) {
        $this->filterSelectOptions[$collumn]=$options;
        return;
    }
    
    /**
     * set column status options
     * @param string $collumn
     * @param array $options
     * @return void
     */
    public function setColumnStatusOptions($collumn,$options) {
        $this->collumnStatusOptions[$collumn]=$options;
        return;
    }
    
    /**
     * set group action options
     * @param string $collumn
     * @param array $options
     * @return void
     */
    public function setGroupActionOptions($collumn,$options){
        $this->groupActionOptions[$collumn]=$options;
        return;
    }
    
    /**
     * @param \Ublaboo\Datagrid\Datagrid $grid
     * @param string $collumn
     * @param array $params
     * @return void
     */
    protected function columnText($grid,$collumn,$params){
        $col = $grid->addColumnText($collumn, $params["title"]);
        (isset($params["sortable"]) and $params["sortable"]==1) ? $col->setSortable() : false;
        
        if(isset($params["filter"])){
            if($params["filter"]=="select"){
                $col->setFilterSelect($this->filterSelectOptions[$collumn]);
            }
            elseif($params["filter"]=="multiselect"){
                $col->setFilterMultiSelect($this->filterSelectOptions[$collumn]);
            }
            else{
                $col->setFilterText();
            }
        }
        isset($params["align"]) ? $col->setAlign($params["align"]) : false;
        (isset($params["defaultHidden"]) and $params["defaultHidden"]==1) ? $col->setDefaultHide() : false;
        (isset($params["attributes"]) and count($params["attributes"])>0) ? $col->addAttributes($params["attributes"]) : false;
        return;
    }
    
    /**
     * @param \Ublaboo\Datagrid\Datagrid $grid
     * @param string $collumn
     * @param array $params
     * @return void
     */
    protected function columnNumber($grid,$collumn,$params){
        $col = $grid->addColumnNumber($collumn, $params["title"]);
        (isset($params["sortable"]) and $params["sortable"]==1) ? $col->setSortable() : false;
        
        if(isset($params["filter"])){
            if($params["filter"]=="select"){
                $col->setFilterSelect($this->filterSelectOptions[$collumn]);
            }
            elseif($params["filter"]=="multiselect"){
                $col->setFilterMultiSelect($this->filterSelectOptions[$collumn]);
            }
            elseif($params["filter"]=="range"){
                $col->setFilterRange();
            }
            else{
                $col->setFilterText();
            }
        }
        
        isset($params["align"]) ? $col->setAlign($params["align"]) : false;
        (isset($params["defaultHidden"]) and $params["defaultHidden"]==1)? $col->setDefaultHide() : false;
        (isset($params["attributes"]) and count($params["attributes"])>0) ? $col->addAttributes($params["attributes"]): false;         
    }
    
    /**
     * @param \Ublaboo\Datagrid\Datagrid $grid
     * @param string $collumn
     * @param array $params
     * @return void
     */
    protected function columnDatetime($grid,$collumn,$params){
        $col = $grid->addColumnDatetime($collumn, $params["title"]);
        (isset($params["sortable"]) and $params["sortable"]==1) ? $col->setSortable() : false;
        
        if(isset($params["filter"])){
            if($params["filter"]=="range"){
                $col->setFilterDateRange();
            }
            else{
                $col->setFilterDate();
            }
        }
        isset($params["align"]) ? $col->setAlign($params["align"]) : false;
        (isset($params["defaultHidden"]) and $params["defaultHidden"]==1) ? $col->setDefaultHide() : false;
        (isset($params["attributes"]) and count($params["attributes"])>0) ? $col->addAttributes($params["attributes"]) : false;
        isset($params["format"]) ? $col->setFormat($params["format"]) : false;
        return;
    }
    
    /**
     * @param \Ublaboo\Datagrid\Datagrid $grid
     * @param string $collumn
     * @param array $params
     * @return void
     */
    protected function columnLink($grid,$collumn,$params){
        $col = $grid->addColumnLink($collumn, $params["title"],$params["href"],$params["params"]);
        (isset($params["sortable"]) and $params["sortable"]==1) ? $col->setSortable() : false;
        isset($params["align"]) ? $col->setAlign($params["align"]) : false;
        (isset($params["defaultHidden"]) and $params["defaultHidden"]==1) ? $col->setDefaultHide() : false;
        (isset($params["attributes"]) and count($params["attributes"])>0) ? $col->addAttributes($params["attributes"]): false;  
        isset($params["class"]) ? $col->setClass($params["class"]) : false;
        isset($params["icon"]) ? $col->setIcon($params["icon"]) : false; 
        isset($params["attributes"]) ? $col->addAttributes($params["attributes"]) : false;
        (isset($params["netTab"]) and $params["netTab"]==1) ? $col->setOpenInNewTab() : false;
        return;        
    }
    
    /** 
     * @param \Ublaboo\Datagrid\Datagrid $grid
     * @param string $collumn
     * @param array $params
     * @return void
     */
    protected function columnStatus($grid,$collumn,$params){
        isset($params["options"]) ? $this->collumnStatusOptions=$params["options"] : false;
        $col=$grid->addColumnStatus($collumn,$params["title"]);
        $col->setOptions($this->collumnStatusOptions);
        isset($params["onchange"]) ? $col->onChange[] = [$this, $params["onchange"]] : false;
        if(isset($params["optionClasses"]) and count($params["optionClasses"])>0){
            foreach($params["optionClasses"] as $key=>$class){
                $grid->getColumn($collumn)->getOption($key)
                     ->setClass($class);
            }
        }
        (isset($params["sortable"]) and $params["sortable"]==1) ? $col->setSortable() : false;
        if(isset($params["filter"])){
            if($params["filter"]=="select"){
                $col->setFilterSelect($this->filterSelectOptions[$collumn]);
            }
            elseif($params["filter"]=="multiselect"){
                $col->setFilterMultiSelect($this->filterSelectOptions[$collumn]);
            }
            else{
                $col->setFilterText();
            }
        }
    }
    
    /**
     * @param \Ublaboo\Datagrid\Datagrid $grid
     * @param array $param
     * @return void;
     */
    protected function toolbarButton($grid,$param) {
        isset($param["parameters"]) ? false: $param["parameters"]=[];
        
        $btn = $grid->addToolbarButton($param["href"],$param["title"],$param["parameters"]);
        
        isset($param["icon"]) ? $btn->setIcon($param["icon"]) : false;
        isset($param["class"]) ? $btn->setClass($param["class"]) : false;
        isset($param["attributes"]) ? $btn->addAttributes($param["attributes"]) : false;
    }
    
    /**
     * @param \Ublaboo\Datagrid\Datagrid $grid
     * @param string $action
     * @param array $params
     * @void
     */
    protected function setAction($grid, $action, $params) {
        isset($params["parameters"]) ? false : $params["parameters"]=null;
        $col=$grid->addAction($action,$params["title"],$params["href"],$params["parameters"]);
        $col->setTitle($params["title"]);

        isset($params["icon"]) ? $col->setIcon($params["icon"]) : false;        
        isset($params["class"]) ? $col->setIcon($params["class"]) : false;
        isset($params["confirm"]) ? $col->setConfirm($params["confirm"]["message"],$params["confirm"]["placeholder"]) : false;
        isset($params["attributes"]) ? $col->addAttributes($params["attributes"]) : false;
        isset($params["text"]) ? $col->setText($params["text"]) : false;
        isset($params["dataAttribute"]) ? $col->setDataAttribute($params["dataAttribute"]["key"],$params["dataAttribute"]["value"]) : false;
    }
    
    /**
     * @param \Ublaboo\Datagrid\Datagrid $grid
     * @param array $params
     * @return void
     * @throws \GridException
     */
    protected function setGroupAction($grid, $params){
        if($params["type"]=="normal"){
            isset($params["name"]["options"]) ? $this->groupActionOptions=$params["name"]["options"] : false;
            count($this->groupActionOptions)==0 ? $this->groupActionOptions=null : false;
            $col=$grid->addGroupAction($params["title"],$this->groupActionOptions);
            $col->onSelect[] = [$this, $params["onSelect"]];
            
        }
        elseif($params["type"]=="text"){
            $col=$grid->addGroupTextAction($params["title"]);
            $col->onSelect[] = [$this, $params["onSelect"]];            
        }
        elseif($params["type"]=="textarea"){
            $col=$grid->addGroupTextareaAction($params["title"]);
            $col->onSelect[] = [$this, $params["onSelect"]];   
        }
        else{
            throw new \BaseModule\GridException("base.controls.grid.invalidGroupActionType");    
        }
        isset($params["class"]) ? $col->setClass($params["class"]) : false;
        if(isset($params["attributes"])){
            foreach ($params["attributes"] as $key => $value) {
                $col->setAttribute($key,$value);
            }
        }
        return;
    }
    
    /**
     * factory to datagrid component
     * @param string $name
     * @return \Ublaboo\DataGrid\DataGrid
     * @throws \GridException
     */
    public function createComponentGrid($name) {
        $grid = new \Ublaboo\DataGrid\DataGrid($this, $name);
        $grid->setDataSource($this->source->getTable());
        
        isset($this->schema["datasource"]["primaryKey"]) ? $grid->setPrimaryKey($this->schema["datasource"]["primaryKey"]) : false;
        
        (isset($this->schema["pagination"]) and $this->schema["pagination"]==false) ? $grid->setPagination(FALSE) : $grid->setItemsPerPageList($this->schema["pagination"]);
        
        (isset($this->schema["hidableCollumns"]) and $this->schema["hidableCollumns"]==1) ? $grid->setColumnsHideable() : false;
        (isset($this->schema["setOuterFilterRendering"]) and $this->schema["setOuterFilterRendering"]==1) ? $grid->setOuterFilterRendering() : false;
        if(isset($this->schema["collapsibleFilters"]) and $this->schema["collapsibleFilters"]==1){
            $grid->hasOuterFilterRendering() ? $grid->setCollapsibleOuterFilters() : false;
        }
        
        if(isset($this->schema["toolbarButtons"]) and count($this->schema["toolbarButtons"])>0){
            foreach($this->schema["toolbarButtons"] as $toolbarButton){
                $this->toolbarButton($grid,$toolbarButton);
            }
        }
        
        if(isset($this->schema["collumns"]) and count($this->schema["collumns"])>0){
            foreach($this->schema["collumns"] as $collumn=>$params){
                isset($params["type"]) ? false : $params["type"]="text";
                isset($params["title"]) ? false : $params["title"]=$collumn;
        
                switch($params["type"]){
                    case "text":
                        $this->columnText($grid, $collumn, $params);
                        break;
                
                    case "number":
                        $this->columnNumber($grid,$collumn,$params);
                        break;
                
                    case "link":
                        $this->columnLink($grid, $collumn, $params);
                        break;
                
                    case "datetime":
                        $this->columnDatetime($grid, $collumn, $params);
                        break;

                    case "status":
                        $this->columnStatus($grid, $collumn, $params);
                        break;
                    
                    default:
                        throw new \BaseModule\GridException("base.controls.grid.invalidColumnType");
                }
            }
        }
        if(isset($this->schema["actions"]) and count($this->schema["actions"])>0){
            foreach($this->schema["actions"] as $action=>$params){
                $this->setAction($grid, $action, $params);
            }
        }
        if(isset($this->schema["groupActions"]) and count($this->schema["groupActions"])>0){
            foreach($this->schema["groupActions"] as $params){
                $this->setGroupAction($grid, $params);
            }            
        }
        return $grid;
    }
}
