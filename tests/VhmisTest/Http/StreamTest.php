<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\Http;

use Vhmis\Http\Stream;

/**
 * Test case by phly/http
 */
class StreamTest extends \PHPUnit\Framework\TestCase
{

    public $tmpnam;

    public function setUp()
    {
        $this->tmpnam = null;
        $this->stream = new Stream(fopen('php://memory', 'wb+'));
    }

    public function tearDown()
    {
        if ($this->tmpnam && file_exists($this->tmpnam)) {
            unlink($this->tmpnam);
        }
    }

    public function testCanInstantiateWithStreamIdentifier()
    {
        $this->assertInstanceOf('Vhmis\Http\Stream', $this->stream);
    }

    public function testCanInstantiteWithStreamResource()
    {
        $resource = fopen('php://memory', 'wb+');
        $stream = new Stream($resource);
        $this->assertInstanceOf('Vhmis\Http\Stream', $stream);
    }

    public function testIsReadableReturnsFalseIfStreamIsNotReadable()
    {
        $this->tmpnam = tempnam(sys_get_temp_dir(), 'phly');
        $stream = new Stream(fopen($this->tmpnam, 'w'));
        $this->assertFalse($stream->isReadable());
    }

    public function testIsWritableReturnsFalseIfStreamIsNotWritable()
    {
        $stream = new Stream(fopen('php://memory', 'r'));
        $this->assertFalse($stream->isWritable());
    }

    public function testToStringRetrievesFullContentsOfStream()
    {
        $message = 'foo bar';
        $this->stream->write($message);
        $this->assertEquals($message, (string) $this->stream);
    }

    public function testDetachReturnsResource()
    {
        $resource = fopen('php://memory', 'wb+');
        $stream = new Stream($resource);
        $this->assertSame($resource, $stream->detach());
    }

    public function testPassingInvalidStreamResourceToConstructorRaisesException()
    {
        $this->expectException('InvalidArgumentException');
        $stream = new Stream(['  THIS WILL NOT WORK  ']);
    }

    public function testStringSerializationReturnsEmptyStringWhenStreamIsNotReadable()
    {
        $this->tmpnam = tempnam(sys_get_temp_dir(), 'phly');
        file_put_contents($this->tmpnam, 'FOO BAR');
        $stream = new Stream(fopen($this->tmpnam, 'w'));

        $this->assertEquals('', $stream->__toString());
    }

    public function testCloseClosesResource()
    {
        $this->tmpnam = tempnam(sys_get_temp_dir(), 'phly');
        $resource = fopen($this->tmpnam, 'wb+');
        $stream = new Stream($resource);
        $stream->close();
        $this->assertFalse(is_resource($resource));
    }

    public function testCloseUnsetsResource()
    {
        $this->tmpnam = tempnam(sys_get_temp_dir(), 'phly');
        $resource = fopen($this->tmpnam, 'wb+');
        $stream = new Stream($resource);
        $stream->close();

        $this->assertNull($stream->detach());
    }

    public function testCloseDoesNothingAfterDetach()
    {
        $this->tmpnam = tempnam(sys_get_temp_dir(), 'phly');
        $resource = fopen($this->tmpnam, 'wb+');
        $stream = new Stream($resource);
        $detached = $stream->detach();

        $stream->close();
        $this->assertTrue(is_resource($detached));
        $this->assertSame($resource, $detached);
    }

    public function testSize()
    {
        $this->tmpnam = tempnam(sys_get_temp_dir(), 'phly');
        $resource = fopen($this->tmpnam, 'w+b');
        $stream = new Stream($resource);
        $this->assertEquals(0, $stream->getSize());
        $stream->write('aaaa');
        $this->assertEquals(4, $stream->getSize());
        $stream->write('aaaa');
        $this->assertEquals(8, $stream->getSize());
        $stream->detach();
        $this->assertEquals(null, $stream->getSize());
    }

    public function testTellReportsCurrentPositionInResource()
    {
        $this->tmpnam = tempnam(sys_get_temp_dir(), 'phly');
        file_put_contents($this->tmpnam, 'FOO BAR');
        $resource = fopen($this->tmpnam, 'wb+');
        $stream = new Stream($resource);

        fseek($resource, 2);

        $this->assertEquals(2, $stream->tell());
    }

    public function testTellReturnsFalseIfResourceIsDetached()
    {
        $this->tmpnam = tempnam(sys_get_temp_dir(), 'phly');
        file_put_contents($this->tmpnam, 'FOO BAR');
        $resource = fopen($this->tmpnam, 'wb+');
        $stream = new Stream($resource);

        fseek($resource, 2);
        $stream->detach();
        $this->assertFalse($stream->tell());
    }

    public function testEofReportsFalseWhenNotAtEndOfStream()
    {
        $this->tmpnam = tempnam(sys_get_temp_dir(), 'phly');
        file_put_contents($this->tmpnam, 'FOO BAR');
        $resource = fopen($this->tmpnam, 'wb+');
        $stream = new Stream($resource);

        fseek($resource, 2);
        $this->assertFalse($stream->eof());
    }

    public function testEofReportsTrueWhenAtEndOfStream()
    {
        $this->tmpnam = tempnam(sys_get_temp_dir(), 'phly');
        file_put_contents($this->tmpnam, 'FOO BAR');
        $resource = fopen($this->tmpnam, 'wb+');
        $stream = new Stream($resource);

        while (!feof($resource)) {
            fread($resource, 4096);
        }
        $this->assertTrue($stream->eof());
    }

    public function testEofReportsTrueWhenStreamIsDetached()
    {
        $this->tmpnam = tempnam(sys_get_temp_dir(), 'phly');
        file_put_contents($this->tmpnam, 'FOO BAR');
        $resource = fopen($this->tmpnam, 'wb+');
        $stream = new Stream($resource);

        fseek($resource, 2);
        $stream->detach();
        $this->assertTrue($stream->eof());
    }

    public function testIsSeekableReturnsTrueForReadableStreams()
    {
        $this->tmpnam = tempnam(sys_get_temp_dir(), 'phly');
        file_put_contents($this->tmpnam, 'FOO BAR');
        $resource = fopen($this->tmpnam, 'wb+');
        $stream = new Stream($resource);
        $this->assertTrue($stream->isSeekable());
    }

    public function testIsSeekableReturnsFalseForDetachedStreams()
    {
        $this->tmpnam = tempnam(sys_get_temp_dir(), 'phly');
        file_put_contents($this->tmpnam, 'FOO BAR');
        $resource = fopen($this->tmpnam, 'wb+');
        $stream = new Stream($resource);
        $stream->detach();
        $this->assertFalse($stream->isSeekable());
    }

    public function testSeekAdvancesToGivenOffsetOfStream()
    {
        $this->tmpnam = tempnam(sys_get_temp_dir(), 'phly');
        file_put_contents($this->tmpnam, 'FOO BAR');
        $resource = fopen($this->tmpnam, 'wb+');
        $stream = new Stream($resource);
        $this->assertTrue($stream->seek(2));
        $this->assertEquals(2, $stream->tell());
    }

    public function testRewindResetsToStartOfStream()
    {
        $this->tmpnam = tempnam(sys_get_temp_dir(), 'phly');
        file_put_contents($this->tmpnam, 'FOO BAR');
        $resource = fopen($this->tmpnam, 'wb+');
        $stream = new Stream($resource);
        $this->assertTrue($stream->seek(2));
        $stream->rewind();
        $this->assertEquals(0, $stream->tell());
    }

    public function testSeekReturnsFalseWhenStreamIsDetached()
    {
        $this->tmpnam = tempnam(sys_get_temp_dir(), 'phly');
        file_put_contents($this->tmpnam, 'FOO BAR');
        $resource = fopen($this->tmpnam, 'wb+');
        $stream = new Stream($resource);
        $stream->detach();
        $this->assertFalse($stream->seek(2));
        $this->assertEquals(0, ftell($resource));
    }

    public function testIsWritableReturnsFalseWhenStreamIsDetached()
    {
        $this->tmpnam = tempnam(sys_get_temp_dir(), 'phly');
        file_put_contents($this->tmpnam, 'FOO BAR');
        $resource = fopen($this->tmpnam, 'wb+');
        $stream = new Stream($resource);
        $stream->detach();
        $this->assertFalse($stream->isWritable());
    }

    public function testWriteReturnsFalseWhenStreamIsDetached()
    {
        $this->tmpnam = tempnam(sys_get_temp_dir(), 'phly');
        file_put_contents($this->tmpnam, 'FOO BAR');
        $resource = fopen($this->tmpnam, 'wb+');
        $stream = new Stream($resource);
        $stream->detach();
        $this->assertFalse($stream->write('bar'));
    }

    public function testIsReadableReturnsFalseWhenStreamIsDetached()
    {
        $this->tmpnam = tempnam(sys_get_temp_dir(), 'phly');
        file_put_contents($this->tmpnam, 'FOO BAR');
        $resource = fopen($this->tmpnam, 'wb+');
        $stream = new Stream($resource);
        $stream->detach();
        $this->assertFalse($stream->isReadable());
    }

    public function testReadReturnsEmptyStringWhenStreamIsDetached()
    {
        $this->tmpnam = tempnam(sys_get_temp_dir(), 'phly');
        file_put_contents($this->tmpnam, 'FOO BAR');
        $resource = fopen($this->tmpnam, 'r');
        $stream = new Stream($resource);
        $stream->detach();
        $this->assertEquals('', $stream->read(4096));
    }

    public function testReadReturnsEmptyStringWhenAtEndOfFile()
    {
        $this->tmpnam = tempnam(sys_get_temp_dir(), 'phly');
        file_put_contents($this->tmpnam, 'FOO BAR');
        $resource = fopen($this->tmpnam, 'r');
        $stream = new Stream($resource);
        while (!feof($resource)) {
            fread($resource, 4096);
        }
        $this->assertEquals('', $stream->read(4096));
    }

    public function testGetContentsReturnsEmptyStringIfStreamIsNotReadable()
    {
        $this->tmpnam = tempnam(sys_get_temp_dir(), 'phly');
        file_put_contents($this->tmpnam, 'FOO BAR');
        $resource = fopen($this->tmpnam, 'w');
        $stream = new Stream($resource);
        $this->assertEquals('', $stream->getContents());
    }

    public function testGetMetadataReturnsAllMetadataWhenNoKeyPresent()
    {
        $this->tmpnam = tempnam(sys_get_temp_dir(), 'PHLY');
        $resource = fopen($this->tmpnam, 'r+');
        $stream = new Stream($resource);

        $expected = stream_get_meta_data($resource);
        $test = $stream->getMetadata();

        $this->assertEquals($expected, $test);
    }

    public function testGetMetadataReturnsDataForSpecifiedKey()
    {
        $this->tmpnam = tempnam(sys_get_temp_dir(), 'PHLY');
        $resource = fopen($this->tmpnam, 'r+');
        $stream = new Stream($resource);

        $metadata = stream_get_meta_data($resource);
        $expected = $metadata['uri'];

        $test = $stream->getMetadata('uri');

        $this->assertEquals($expected, $test);
    }

    public function testGetMetadataReturnsNullIfNoDataExistsForKey()
    {
        $this->tmpnam = tempnam(sys_get_temp_dir(), 'PHLY');
        $resource = fopen($this->tmpnam, 'r+');
        $stream = new Stream($resource);

        $this->assertNull($stream->getMetadata('TOTALLY_MADE_UP'));
    }
}
