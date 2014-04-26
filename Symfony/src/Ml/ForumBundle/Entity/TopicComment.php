<?php

namespace Ml\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TopicComment
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ml\ForumBundle\Entity\TopicCommentRepository")
 */
class TopicComment
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Ml\ForumBundle\Entity\Topic")
    */
    private $topic;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Ml\ForumBundle\Entity\Comment")
    */
    private $comment;
	
    /**
     * Set topic
     *
     * @param \stdClass $topic
     * @return TopicComment
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;
    
        return $this;
    }

    /**
     * Get topic
     *
     * @return \stdClass 
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Set comment
     *
     * @param \stdClass $comment
     * @return TopicComment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    
        return $this;
    }

    /**
     * Get comment
     *
     * @return \stdClass 
     */
    public function getComment()
    {
        return $this->comment;
    }
}
