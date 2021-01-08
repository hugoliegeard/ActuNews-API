<?php


namespace App\EventListener;


use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategorySubscriber implements \Doctrine\Common\EventSubscriber
{

    /**
     * @var SluggerInterface
     */
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist
        ];
    }

    /**
     * Cette fonction se déclenche juste avant l'insertion
     * d'un élément dans la BDD.
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->generateSlug($args);
    }

    private function generateSlug(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof Category) {
            return;
        }

        $entity->setAlias(
            $this->slugger->slug(
                $entity->getName()
            )
        );

    }
}