<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace FatalNetwork\CookieConsentBundle\Form;

use FatalNetwork\CookieConsentBundle\Cookie\CookieChecker;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CookieConsentType extends AbstractType
{
    /**
     * @var CookieChecker
     */
    protected $cookieChecker;

    /**
     * @var array
     */
    protected $cookieCategories;

    /**
     * @var bool
     */
    protected $csrfProtection;

    public function __construct(
        CookieChecker $cookieChecker,
        array $cookieCategories,
        bool $csrfProtection = true
    ) {
        $this->cookieChecker           = $cookieChecker;
        $this->cookieCategories        = $cookieCategories;
        $this->csrfProtection          = $csrfProtection;
    }

    /**
     * Build the cookie consent form.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        foreach ($this->cookieCategories as $category) {
            $builder->add($category, CheckboxType::class, [
                'label' => false,
                'data' => $this->cookieChecker->isCategoryAllowedByUser($category) ? true : false,
                'row_attr' => [
                    'class' => 'form-switch',
                ],
            ]);
        }

        $builder->add('save', SubmitType::class, ['label' => 'fn_cookie_consent.save', 'attr' => ['class' => 'btn fn-cookie-consent__btn']]);
        $builder->add('use_only_functional_cookies', SubmitType::class, ['label' => 'fn_cookie_consent.use_only_functional_cookies', 'attr' => ['class' => 'btn fn-cookie-consent__btn']]);
        $builder->add('use_all_cookies', SubmitType::class, ['label' => 'fn_cookie_consent.use_all_cookies', 'attr' => ['class' => 'btn fn-cookie-consent__btn fn-cookie-consent__btn--secondary']]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();

            foreach ($this->cookieCategories as $category) {

                if (isset($data['use_all_cookies'])) {
                    $data[$category] = true;
                } elseif (isset($data['use_only_functional_cookies'])) {
                    $data[$category] = false;
                } else {
                    $data[$category] = isset($data[$category]) ? $data[$category] : false;
                }
            }

            $event->setData($data);
        });
    }

    /**
     * Default options.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'FNCookieConsentBundle',
            'csrf_protection' => $this->csrfProtection,
        ]);
    }
}
