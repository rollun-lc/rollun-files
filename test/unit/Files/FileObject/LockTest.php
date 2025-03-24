<?php

namespace rollun\test\unit\Files\FileObject;

use rollun\files\FileObject;
use rollun\test\unit\Files\FileObject\FileObjectAbstractTest;

class LockTest extends FileObjectAbstractTest
{

    /**
     *
     * @var FileObject
     */
    protected $fileObject1;

    /**
     *
     * @var FileObject
     */
    protected $fileObject2;

    protected function setUp(): void
    {
        parent::setUp();
        $fileObject = $this->getFileObject();
        $fileObject->fwriteWithCheck("first string \n second string");
        $filename = $fileObject->getRealPath();
        unset($fileObject);
        $this->fileObject1 = new FileObject($filename);
        $this->fileObject2 = new FileObject($filename);
    }

    protected function tearDown(): void
    {
        $this->fileObject1->unlock();
        $this->fileObject2->unlock();
        unset($this->fileObject1);
        unset($this->fileObject2);
    }

    public function testObjects()
    {
        $this->assertEquals($this->fileObject1, $this->fileObject2);
    }

    public function testLockLock()
    {
        //$lockMode LOCK_SH or LOCK_EX
        $this->fileObject1->lock(LOCK_SH);
        $this->assertTrue($this->fileObject1->lock(LOCK_SH));
        $this->fileObject1->unlock();
        $this->fileObject1->lock(LOCK_SH);
        $this->assertTrue($this->fileObject1->lock(LOCK_EX));
        $this->fileObject1->unlock();
        $this->fileObject1->lock(LOCK_EX);
        $this->assertTrue($this->fileObject1->lock(LOCK_SH));
        $this->fileObject1->unlock();
        $this->fileObject1->lock(LOCK_EX);
        $this->assertTrue($this->fileObject1->lock(LOCK_EX));
        $this->fileObject1->unlock();
    }

    public function testLock12()
    {
        //$lockMode LOCK_SH or LOCK_EX
        $this->fileObject1->lock(LOCK_SH);
        $this->assertTrue($this->fileObject2->lock(LOCK_SH));
        $this->expectException(\RuntimeException::class);
        $this->fileObject2->lock(LOCK_EX);
    }

    /**
     * flock() on Windows: uses mandatory locking instead of advisory locking
     * flock() on Linux: utilizes ADVISORY locking only; that is, other processes may ignore the lock completely
     */
    public function testLockRead()
    {
        //$lockMode LOCK_SH or LOCK_EX
        $this->assertTrue($this->fileObject1->flock(LOCK_EX));

        /**
         * Linux
         */
        if (stripos(php_uname(), 'linux') !== false) {
            return;
        }

        $this->fileObject2->rewind();
        $actual = $this->fileObject2->current();
        $this->fileObject2->next();
        $actual = $this->fileObject2->current();
        $this->assertFalse($actual);
        $actual = $this->fileObject2->key();
        $this->fileObject2->next();
        $actual = $this->fileObject2->current();
        $this->assertFalse($actual);
        $this->fileObject2->rewind();
        $this->fileObject2->current();
        $this->expectException(\RuntimeException::class);
        $this->fileObject2->fgets();
    }

}
