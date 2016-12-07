<?php
/**
 * Created by PhpStorm.
 * User: fabien
 * Date: 07/12/16
 * Time: 11:08
 */

namespace AppBundle\Twig;


use AppBundle\Service\MarkdownTransformer;

class MarkdownExtension extends \Twig_Extension
{
    /**
     * @var MarkdownTransformer
     */
    private $markdownTransformer;


    /**
     * MarkdownExtension constructor.
     */
    public function __construct(MarkdownTransformer $markdownTransformer)
    {
        $this->markdownTransformer = $markdownTransformer;
    }

    public function getName()
    {
        return 'app_markdown';
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('markdownify', array($this, 'parseMarkdown'), ['is_safe'=>['html']])
        ];
    }

    public function parseMarkdown($str) {
        return $this->markdownTransformer->parse($str);
    }

}
