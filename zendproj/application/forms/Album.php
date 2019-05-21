<?php
use Zend\Form\Element;
use Zend\Form\Form;

class Application_Form_Album extends Zend_Form
{   private $_image;

    public function __construct($options = array()) {
        if(isset($options['image'])) {
            $this->_image= $options['image'];
            unset($options['image']);
        }
        return parent::__construct($options);
    }

    public function init() {
        echo "<div class=form>";
        $this->setName('album');
        $id = new Zend_Form_Element_Hidden('id');
        $id->addFilter('Int');
        $artist = new Zend_Form_Element_Text('artist');
        $artist->setLabel('Artist:')
            ->setRequired(true)
            ->setAttrib('placeholder', 'Artist')
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');

        echo "</div>";
        echo "<div class=form>";
        $title = new Zend_Form_Element_Text('title');
        $title->setLabel('Title:')
            ->setRequired(true)
            ->setAttrib('placeholder', 'Title')
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
        echo "</div>";
        echo "<div class=form>";
        $novel = new Zend_Form_Element_Text('novel');
        $novel->setLabel('Novel:')
            ->setRequired(true)
            ->setAttrib('placeholder', 'Novel')
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');
        echo "</div>";
        echo "<div class=form-group>";
        $multiCheckbox = new  Zend_Form_Element_MultiCheckbox('multiCheckbox');
        $multiCheckbox->setLabel('Author name:')
            ->setRequired(true)
            ->addValidator('NotEmpty') 
            ->setMultiOptions(array('George West' => 'George West', 'Martin Nolan' => 'Martin Nolan', 'Jack Mathers' => 'Jack Mathers', 'Peter Timberlake' => 'Peter Timberlake'));
        $selectlang = new Zend_Form_Element_Select('selectlang');
        $selectlang->setLabel('Language:')
            ->setRequired(true)
            ->setMultiOptions(array('Japanese' => 'Japanese', 'English' => 'English', 'Spanish' => 'Spanish'));
        $multiselect = new Zend_Form_Element_Multiselect('multiselect');
        $multiselect->setLabel('Places sold:')
            ->setRequired(true)
            ->setMultiOptions(array('USA' => 'USA', 'Europe' => 'Europe', 'Hong Kong' => 'Hong Kong', 'Japan' => 'Japan', 'Singapore' => 'Singapore', 'India' => 'India'));
        $file = new Zend_Form_Element_File('profile_pic');
        $file->setLabel('Upload an image:')
             ->setDestination(APPLICATION_PATH .'/../public/upload');
        // ensure only 1 file
        $file->addValidator('Count', false, 1);
        // limit to 1M
        $file->addValidator('Size', false, 1024000);
        // only JPEG, PNG, and GIFs
        $file->addValidator('Extension', false, 'jpg,png,gif,jpeg');
        $file->setValueDisabled(true);

        if($this->_image){
            $file->setDescription(
            '<img class = "preview-img" src="../../../../public/upload/'.$this->_image.'"></img>')
            ->setDecorators(
            array('File',
            array('ViewScript',
            array('viewScript' => 'index/file.phtml', 'placement' => false)
            )));
        }
        $this->addElement($file, 'profile_pic');
        $this->setAttrib('enctype', 'multipart/form-data');
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        $this->addElements(array($id, $artist, $title, $novel, $multiCheckbox, $selectlang, $multiselect, $file, $submit));
    }
}
