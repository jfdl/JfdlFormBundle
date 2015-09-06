<?php

namespace Jfdl\FormBundle\Form\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class AjaxEntityTransformer implements DataTransformerInterface
{
    protected $repo;
    protected $multiple;
    protected $property;

    public function __construct(ManagerRegistry $registry, $class, $multiple, $property)
    {
        $this->repo = $registry->getManager()->getRepository($class);
        $this->multiple = $multiple;
        $this->property = $property;
    }

    public function transform($value)
    {
        $out = array();
        if (($value instanceof PersistentCollection) || ($value instanceof ArrayCollection)) {
            foreach($value as $entity) {
                $out[$entity->getId()] = $this->getText($entity);
            }
        } elseif (is_object($value)) {
            $out[$value->getId()] = $this->getText($value);
        } else {
            return array();
        }


        return $out;

    }

    public function reverseTransform($value)
    {

        if (!$value) {
            return $this->multiple ? array() : null;
        }

        if ($this->multiple) {


            $qb = $this->repo->createQueryBuilder('entity');
            $qb->where('entity.id IN (:ids)')
                ->setParameter('ids', $value)
            ;

            return new ArrayCollection($qb->getQuery()->execute());
        }

        $entity = $this->repo->find($value);

        if (!$entity) {
            throw new TransformationFailedException(sprintf(
                'Entity "%s" with id "%s" does not exist.',
                $this->repo->getClassName(),
                $value
            ));
        }

        return $entity;
    }

    protected function getText($object)
    {
        if (!$this->property || !class_exists('Symfony\Component\PropertyAccess\PropertyAccess')) {
            return (string) $object;
        }

        $accessor = PropertyAccess::getPropertyAccessor();

        return $accessor->getValue($object, $this->property);
    }
};