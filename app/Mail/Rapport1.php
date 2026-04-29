<?php

namespace GEICOM\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Rapport1 extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $pdfPath;
    protected $subj;

    public function __construct($pdfPath,$subject)
    {

        $this->pdfPath=$pdfPath;
        $this->subj=$subject;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $myPdfPath=$this->pdfPath;
        $mySubject=$this->subj;

        return $this->markdown('email.rapport0')->subject($mySubject)->attach('public/pdf/'.$myPdfPath[0],[
            'as' => $myPdfPath[0],
            'mime' => 'application/pdf'])
            ->attach('public/pdf/'.$myPdfPath[1],[
            'as' => $myPdfPath[1],
            'mime' => 'application/pdf']);
    }
}
