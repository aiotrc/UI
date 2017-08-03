<?php

namespace SmartCity\UserBundle\Console\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExpireVerificationToken extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('SmartCity:user:verification:expire')
            ->setDescription('Check verification tokens')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Start processing</info>');
        $this->expireTimeoutVerificationTokens($output);
        $this->expireTimeoutForgetPasswordTokens($output);
        $output->writeln('<info>End processing</info>');
    }

    protected function expireTimeoutVerificationTokens(OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $verificationTokenModel =  $em->getRepository('SmartCityUserBundle:UserVerificationToken');
        $verificationTokenModel->expireTimeoutTokens();
    }

    protected function expireTimeoutForgetPasswordTokens(OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $verificationTokenModel =  $em->getRepository('SmartCityUserBundle:UserForgotPassword');
        $verificationTokenModel->expireTimeoutTokens();
    }
}