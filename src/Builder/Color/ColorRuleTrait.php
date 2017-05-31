<?php

namespace Kiboko\Component\AkeneoProductValuesPackage\Builder\Color;

use Composer\Composer;
use Kiboko\Component\AkeneoProductValuesPackage\Model\ColorCMYK;
use Kiboko\Component\AkeneoProductValuesPackage\Model\ColorHSL;
use Kiboko\Component\AkeneoProductValuesPackage\Model\ColorRGB;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

trait ColorRuleTrait
{
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param Composer $composer
     * @return bool
     */
    public function interact(InputInterface $input, OutputInterface $output, Composer $composer)
    {
        $helper = new QuestionHelper();

        if (!in_array($this->getReferenceClass(), [self::TYPE_COLOR_RGB, self::TYPE_COLOR_CMYK, self::TYPE_COLOR_HSL])) {
            $fieldNameQuestion = new Question(
                sprintf(
                    'Please chose color type: [<info>%s</info>]',
                    self::TYPE_COLOR_RGB
                ),
                self::TYPE_COLOR_RGB
            );
            $fieldNameQuestion->setValidator(function ($value) {
                if (!in_array($this->getReferenceClass(), ['rgb', 'cmyk', 'hsl'])) {
                    throw new \RuntimeException(
                        'The color type should be either rgb, cmyk or hsl.'
                    );
                }

                return $value;
            })->setMaxAttempts(2);

            $fieldNameQuestion->setAutocompleterValues([
                'rgb', 'cmyk', 'hsl',
            ]);

            $this->setReferenceClass(
                array_search(
                    $helper->ask($input, $output, $fieldNameQuestion),
                    [
                        ColorRGB::class => 'rgb',
                        ColorCMYK::class => 'cmyk',
                        ColorHSL::class => 'hsl',
                    ]
                )
            );
        }

        if ($this->getFieldName() === null) {
            $fieldNameQuestion = new Question(
                sprintf(
                    'Please enter the field name: [<info>%s</info>]',
                    $this->getDefaultField()
                ),
                $this->getDefaultField()
            );
            $fieldNameQuestion->setValidator(function ($value) {
                if (!preg_match('/^[a-z][A-Za-z0-9]*$/', $value)) {
                    throw new \RuntimeException(
                        'The field name should contain only alphanumeric characters and start with a lowercase letter.'
                    );
                }

                return $value;
            })->setMaxAttempts(2);

            $this->setFieldName(
                $helper->ask($input, $output, $fieldNameQuestion)
            );
        }

        $confirmation = new ConfirmationQuestion(sprintf(
            'You are about to to add a %s reference data of type "%s" in the "%s" field of your Akeneo ProductValue class [<info>yes</info>]',
            static::TYPE,
            $this->getReferenceClass(),
            $this->getFieldName()
        ));

        return $helper->ask($input, $output, $confirmation);
    }

    /**
     * @return string
     */
    public static function getName()
    {
        return sprintf('%s.%s', static::TYPE, static::NAME);
    }
}
