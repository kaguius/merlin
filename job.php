<?php
/************************
 * @author = INFAMA
 * © INFAMA 2015
 *
 */
class AssistantDeveloper extends Vacancy
 implements  PHPDeveloper, ITExpert
{
    const vacancyForBrilliantDevelopers = true;
    public $tasks = array(
        'Participating in the development and maintenance of the company database.',
        'Maintaining system logs',
        'Updating the company website.',
        'Providing technical support to the company\'s staff.',
        'Making data backups.',
        'Troubleshooting computer hardware and software.',
        'Troubleshooting network faults.'
    );
    private $desiredQualities= array(
       'creative',
       'Team player',
       'Integrity and Honesty',
       'Fast learner.',
       'Punctual.',
       'Disciplined.'
    );
    public $drawbacks = null;
    public $sampleProjects = array(); //List here
    public $loveForCoding = 0;
    public $skills = array();
    public $CV = '';
    public $ApplicationLetter = '';
    private $_is_a_developer = null;
    private static $_developer = null;
    
    private function __construct()
    {
        
    }
    public static function getInstance()
    {
        if(is_empty($this->_developer))
        {
            $this->_developer = new AssistantDeveloper;
        }
        
        return $this->_developer;
    }
    public function takeTest($attributes_)
    {
        foreach($attributes_ as $key->$attribute)
        {
            $this->$key = $attribute;
        }
        
        $developer = $this->getInstance();
        
        return $this->offerJob($developer);
    }
    public static function offerJob(AssistantDeveloper $you) {
        return (self::hasTheSkills($you) && self::hasWowFactor($you));
    }
    private static function hasTheSkills($developer) {
        $desiredSkills = array('Object-oriented PHP', 'Advanced MySQL','HTML','CSS/3','JS', 'Mobile Developer');
        return (
            count(array_intersect($developer->skills, $desiredSkills)) > 1 &&
            (int)$developer->loveForCoding > 1 << 30
        );
    }
    private static function hasWowFactor($developer) {
        return ($developer instanceof creativeThinker &&
            sizeof($developer->brain) > floatval(strpad('1', 100, '0')) &&
            property_exists($developer, 'hungerForNewTechnologies')
            );
    }
}
$you = AssistantDeveloper::getInstance();
//Amend this
$attributes = array(
                    'CV'                =>'My Awesome CV.ext', //
                    'ApplicationLetter' =>'Awesomeness.ext',
                    'drawbacks'         =>array(), //indicate
                    'sampleProjects'    =>array(), //indicate
                    'skills'            =>array(), //indicate
                    'loveForCoding'     =>10 //indicate
                    );
if($you->takeTest($attributes)){
    try
    {
        $mail = new Email();
        $mail->to = 'jobs@infama.com';
        $mail->cc='itsupport@infama.com'; //Optional
        $mail->addAttachment($you->CV);
        $mail->addAttachment($you->ApplicationLetter);
        $mail->send();
    }
    catch(Exception $e)
    {
        $manual = new OldSkull;
        $hard_copies = $manual->print($you->CV,$you->ApplicationLetter);
        $envelop = $manual->putInEnvelop($hard_copies);
        $envelop->seal();
        $envelop->setAddress("Operations Director \n INFAMA Limited \n 1st Floor, Heritan House \n P.O Box 267 00100 \n Nairobi");
        
        $manual->send($envelop);
    }
}
if (true === INFAMA::offerJob($you)) {
    throw new Party();
}
?>
