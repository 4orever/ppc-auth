<?php
namespace PpcAuth\Entity;

use PpcCore\Entity\Model;
use Zend\Form\Annotation;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Annotation\Hydrator("Zend\Hydrator\ObjectProperty")
 * @Annotation\Name("User")
 * @ORM\Entity
 * @ORM\Table(name="PpcAuthUser")
 */
class User extends Model
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Annotation\Exclude()
     */
    public $id;

    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Options({"label":"Username:"})
     * @ORM\Column(type="string")
     */
    public $username;

    /**
     * @Annotation\Type("Zend\Form\Element\Password")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Options({"label":"Password:"})
     * @ORM\Column(type="string")
     */
    public $password;

    /**
     * @Annotation\Exclude()
     * @ORM\Column(type="boolean")
     */
    public $isFreelancer = false;

    /**
     * @Annotation\Type("Zend\Form\Element\Checkbox")
     * @Annotation\Options({"label":"Remember Me ?:"})
     */
    public $rememberme;

    /**
     * @Annotation\Type("Zend\Form\Element\Submit")
     * @Annotation\Attributes({"value":"Submit"})
     */
    public $submit;
}