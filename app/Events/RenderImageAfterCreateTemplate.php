<?php

namespace App\Events;

use App\Events\Event;
use App\Repositories\Template\TemplateInterface;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class RenderImageAfterCreateTemplate extends Event
{
    /**
     * string html
     * @var $content
     */
    private $content;

    /**
     * Filename
     * @var $filename
     */
    private $filename;

    /**
     * Template Id
     * @var $template_id
     */
    private $template_id;

    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($template_id, $content, $title)
    { 
        $this->template_id = $template_id;
        $this->content = $content;
        $this->filename = str_slug($title).'.jpg';
    }

    public function render(TemplateInterface $template)
    {
        $this->content = replace_url_img($this->content);
        \PDF::loadView('api.template.index', ['content' => $this->content])
            ->save(public_path('pdf/tmp.pdf'));
        
        $this->createImage();

        \File::delete(public_path().'/pdf/tmp.pdf');

        return $this->saveImage($template);
    }

    /**
     * Create image
     * @return void
     */
    private function createImage()
    {
        $img = new \Imagick();
        $img->readImage(public_path('pdf/tmp.pdf[0]'));
        $img->setImageFormat('jpg');
        $img->setSize(200, 200);
        $img->writeImage(public_path('images/template/'.$this->filename));
        $img->clear();
        $img->destroy();
    }

    /**
     * Save thumbnail
     * @param  TemplateInterface $template 
     * @return mixed           
     */
    private function saveImage($template)
    {
         $resize = \Image::make(public_path('images/template/'.$this->filename))
            ->resize(200,150)
            ->save(public_path('thumb/template/'.$this->filename));
        
        if (!$resize) return null;

        $template = $template->getById($this->template_id);
        $template->image = [
            'origin' => asset('images/template/'.$this->filename),
            'thumb' =>asset('public/thumb/'.$this->filename)
        ];
        
        return $template->save() ? $template : null;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
