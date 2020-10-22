<?php

namespace App\Uploader;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * class Uploader
 * @package App\Uploader
 */
class Uploader implements UploaderInterface
{
    /**
     * @var SluggerInterface
     */
    private SluggerInterface $slugger;
    /**
     * @var string
     */
    private string $uploadsAbsoluteDir;
    /**
     * @var string
     */
    private string $uploadsRelativeDir;

    /**
     * Uploader constructor
     * @param SluggerInterface $slugger
     * @param string $uploadsAbsoluteDir
     * @param string $uploadsAbsoluteDir
     */
    public function __construct(SluggerInterface $slugger, string $uploadsAbsoluteDir, string $uploadsRelativeDir)
    {
       $this->slugger = $slugger;
       $this->uploadsAbsoluteDir = $uploadsAbsoluteDir;
       $this->uploadsRelativeDir = $uploadsRelativeDir;
    }


    /**
     * @inheritdoc
     */
    public function upload(UploadedFile $file): string
    {
        $filename = sprintf(
            "%s_%s.%s",
            $this->slugger->slug($file->getClientOriginalName()),
                uniqid(),
                $file->getClientOriginalExtension()
            );

        return $file->move( $this->uploadsRelativeDir, $filename);
    }

 
}