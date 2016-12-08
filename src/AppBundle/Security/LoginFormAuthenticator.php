<?php
/**
 * Created by PhpStorm.
 * User: fabien
 * Date: 08/12/16
 * Time: 09:48
 */

namespace AppBundle\Security;


use AppBundle\Form\LoginForm;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var UserPasswordEncoder
     */
    private $userPasswordEncoder;

    public function __construct(
        FormFactoryInterface $formFactory,
        EntityManager $em,
        Router $router,
        UserPasswordEncoder $userPasswordEncoder
    ) {
        $this->formFactory = $formFactory;
        $this->em = $em;
        $this->router = $router;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    protected function getLoginUrl()
    {
        return $this->router->generate('security_login');
    }

    public function getCredentials(Request $request)
    {
        $isLoginSubmit = $request->getPathInfo(
            ) === '/login' && $request->isMethod('POST');
        if (!$isLoginSubmit) {
            //Skip authenticate the user
            return null;
        }

        $form = $this->formFactory->create(LoginForm::class);

        $form->handleRequest($request);

        $data = $form->getData();

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $data['_username']
        );

        return $data;// pass to getUser credentials

    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $username = $credentials['_username'];

        // If a user is found go to checkCredentials to check password Otherwise if no user found authentication failed
        return $this->em->getRepository('AppBundle:User')->findOneBy(
            ['email' => $username]
        );
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $password = $credentials['_password'];
        if ($this->userPasswordEncoder->isPasswordValid($user, $password)) {
            return true;
        }

        return false;
    }

    protected function getDefaultSuccessRedirectUrl()
    {
        return $this->router->generate('homepagegenus');
    }
}
