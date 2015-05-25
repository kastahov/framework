<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 * @copyright ©2009-2015
 */
namespace Spiral\Components\Http\Input;

use Psr\Http\Message\UploadedFileInterface;
use Spiral\Components\Http\StreamWrapper;

/**
 * Type hinting.
 *
 * @method UploadedFileInterface|null get($name, $default = null)
 * @method UploadedFileInterface[] all()
 * @method UploadedFileInterface[] fetch(array $keys, $fill = false, $filler = null)
 * @method \ArrayIterator|UploadedFileInterface[] getIterator()
 */
class FileBag extends InputBag
{
    /**
     * Get URI (temporary "filename") associated with UploadedFile resource.
     *
     * @param string $name
     * @return null|string
     */
    public function uri($name)
    {
        if ($uploadedFile = $this->get($name))
        {
            return StreamWrapper::getUri($uploadedFile->getStream());
        }

        return null;
    }
}