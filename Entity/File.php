<?php

namespace Melodia\FileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File as UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="FileRepository")
 * @ORM\Table(name="files")
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class File
{
    const REPOSITORY = 'MelodiaFileBundle:File';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Groups({"getAllFiles", "getOneFile", "postFile"})
     */
    protected $id;

    /**
     * @Vich\UploadableField(mapping="file", fileNameProperty="fileName")
     */
    protected $file;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"getOneFile"})
     */
    protected $fileName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Groups({"getAllFiles", "getOneFile", "postFile"})
     */
    protected $fileUri;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Groups({"getAllFiles", "getOneFile"})
     */
    protected $uploadedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Groups({"getOneFile"})
     */
    protected $deletedAt;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
    }

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     * @return File
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @ORM\PrePersist
     */
    public function setUploadedAt()
    {
        $this->uploadedAt = new \DateTime();

        return $this;
    }

    /**
     * Get uploadedAt
     *
     * @return \DateTime
     */
    public function getUploadedAt()
    {
        return $this->uploadedAt;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * @return File
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set fileUri
     *
     * @param string $fileUri
     * @return File
     */
    public function setFileUri($fileUri)
    {
        $this->fileUri = $fileUri;

        return $this;
    }

    /**
     * Get fileUri
     *
     * @return string
     */
    public function getFileUri()
    {
        return $this->fileUri;
    }
}
