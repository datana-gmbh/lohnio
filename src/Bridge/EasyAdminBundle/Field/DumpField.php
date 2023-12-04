<?php

declare(strict_types=1);

namespace App\Bridge\EasyAdminBundle\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;

final class DumpField implements FieldInterface
{
    use FieldTrait;

    /**
     * @param null|false|string $label
     */
    public static function new(string $propertyName, $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setTemplatePath('admin/global/fields/dump.html.twig');
    }
}
