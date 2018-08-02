<?php
/**
 * This file is part of the FreeDSx SNMP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FreeDSx\Snmp\Module\Authentication;

use FreeDSx\Snmp\Message\MessageHeader;
use FreeDSx\Snmp\Message\Request\MessageRequestV3;
use FreeDSx\Snmp\Message\ScopedPduRequest;
use FreeDSx\Snmp\Message\Security\UsmSecurityParameters;
use FreeDSx\Snmp\Module\Authentication\AuthenticationModule;
use FreeDSx\Snmp\Module\Authentication\AuthenticationModuleInterface;
use FreeDSx\Snmp\OidList;
use FreeDSx\Snmp\Request\GetRequest;
use PhpSpec\ObjectBehavior;

class AuthenticationModuleSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('sha1');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AuthenticationModule::class);
    }

    function it_should_implement_the_AuthenticationModuleInterface()
    {
        $this->shouldImplement(AuthenticationModuleInterface::class);
    }

    function it_should_get_the_supported_authentication_mechanisms()
    {
        $this::supports()->shouldBeEqualTo([
            'md5',
            'sha1',
            'sha224',
            'sha256',
            'sha384',
            'sha512',
        ]);
    }

    function it_should_hash_a_value_with_md5()
    {
        $this->beConstructedWith('md5');
        $this->hash('foobar123')->shouldBeEqualTo(hex2bin('ae2d699aca20886f6bed96a0425c6168'));
    }

    function it_should_hash_a_value_with_sha1()
    {
        $this->beConstructedWith('sha1');
        $this->hash('foobar123')->shouldBeEqualTo(hex2bin('6FFD8B80F2A76CA670AE33AB196F7936D59FB43B'));
    }

    function it_should_hash_a_value_with_sha224()
    {
        $this->beConstructedWith('sha224');
        $this->hash('foobar123')->shouldBeEqualTo(hex2bin('adc61a6f0296b87c5e30d85cb6913bb795349cbbb9bdbb51046d4076'));
    }

    function it_should_has_a_value_with_sha256()
    {
        $this->beConstructedWith('sha256');
        $this->hash('foobar123')->shouldBeEqualTo(hex2bin('426a1c28c61b7ba258fa3cc300ba7cd3abc11c0d4b585d3ce4a15d6f22d6d363'));
    }

    function it_should_has_a_value_with_sha384()
    {
        $this->beConstructedWith('sha384');
        $this->hash('foobar123')->shouldBeEqualTo(hex2bin('18e0a12833360e8c9dcfab4067d2dbfee9dfd4b16ba6d4807ceef141b89fe934530d04f698bb977e4b919f606f054e49'));
    }

    function it_should_hash_a_value_with_sha512()
    {
        $this->beConstructedWith('sha512');
        $this->hash('foobar123')->shouldBeEqualTo(hex2bin('9430ece67e0222d318ad98a8d74bc7c0edb2041ba38ab72d530c4ede62d9a5be7eb57e193ae8b35c9fa71726950e07537030af8dd6763ae8734d08f189c4d96e'));
    }

    function it_should_throw_an_exception_if_the_hash_fails()
    {
        $this->beConstructedWith('foo');
        $this->shouldThrow(\Throwable::class)->during('hash',['foobar123']);
    }

    /**
     * RFC 3411, A.3.1
     */
    function it_should_generate_a_key_using_md5()
    {
        $this->beConstructedWith('md5');
        $this->generateKey('maplesyrup', hex2bin('000000000000000000000002'))->shouldBeEqualTo(
            hex2bin('526f5eed9fcce26f8964c2930787d82b')
        );
    }

    /**
     * RFC 3411, A.3.2
     */
    function it_should_generate_a_key_using_sha1()
    {
        $this->beConstructedWith('sha1');
        $this->generateKey('maplesyrup', hex2bin('000000000000000000000002'))->shouldBeEqualTo(
            hex2bin('6695febc9288e36282235fc7151f128497b38f3f')
        );
    }

    function it_should_authenticate_an_outgoing_message_with_md5()
    {
        $this->beConstructedWith('md5');
        $this->authenticateOutgoingMsg(new MessageRequestV3(
            new MessageHeader(1, MessageHeader::FLAG_AUTH_PRIV, 3),
            new ScopedPduRequest(new GetRequest(new OidList()), 'foo'),
            null,
            new UsmSecurityParameters('foo', 1, 1, 'foo')
        ), 'maplesyrup')->getSecurityParameters()->getAuthParams()->shouldBeEqualTo(hex2bin('ac04424fc8acff6b9310a03c'));
    }

    function it_should_authenticate_an_outgoing_message_with_sha1()
    {
        $this->beConstructedWith('sha1');
        $this->authenticateOutgoingMsg(new MessageRequestV3(
            new MessageHeader(1, MessageHeader::FLAG_AUTH_PRIV, 3),
            new ScopedPduRequest(new GetRequest(new OidList()), 'foo'),
            null,
            new UsmSecurityParameters('foo', 1, 1, 'foo')
        ), 'maplesyrup')->getSecurityParameters()->getAuthParams()->shouldBeEqualTo(hex2bin('99eb7f99437037b6743d61c1'));
    }

    function it_should_authenticate_an_outgoing_message_with_sha224()
    {
        $this->beConstructedWith('sha224');
        $this->authenticateOutgoingMsg(new MessageRequestV3(
            new MessageHeader(1, MessageHeader::FLAG_AUTH_PRIV, 3),
            new ScopedPduRequest(new GetRequest(new OidList()), 'foo'),
            null,
            new UsmSecurityParameters('foo', 1, 1, 'foo')
        ), 'maplesyrup')->getSecurityParameters()->getAuthParams()->shouldBeEqualTo(hex2bin('8b79b60bfd4322cf72e60be02e435df2'));
    }

    function it_should_authenticate_an_outgoing_message_with_sha256()
    {
        $this->beConstructedWith('sha256');
        $this->authenticateOutgoingMsg(new MessageRequestV3(
            new MessageHeader(1, MessageHeader::FLAG_AUTH_PRIV, 3),
            new ScopedPduRequest(new GetRequest(new OidList()), 'foo'),
            null,
            new UsmSecurityParameters('foo', 1, 1, 'foo')
        ), 'maplesyrup')->getSecurityParameters()->getAuthParams()->shouldBeEqualTo(hex2bin('13144ad34756ddc29e88cd7a105a5cb360256803ce0f67bb'));
    }

    function it_should_authenticate_an_outgoing_message_with_sha384()
    {
        $this->beConstructedWith('sha384');
        $this->authenticateOutgoingMsg(new MessageRequestV3(
            new MessageHeader(1, MessageHeader::FLAG_AUTH_PRIV, 3),
            new ScopedPduRequest(new GetRequest(new OidList()), 'foo'),
            null,
            new UsmSecurityParameters('foo', 1, 1, 'foo')
        ), 'maplesyrup')->getSecurityParameters()->getAuthParams()->shouldBeEqualTo(hex2bin('94c99f3fb92b42fc77d8d0e104cd4d5708fbe249661a6695ad351dc7744316fb'));
    }

    function it_should_authenticate_an_outgoing_message_with_sha512()
    {
        $this->beConstructedWith('sha512');
        $this->authenticateOutgoingMsg(new MessageRequestV3(
            new MessageHeader(1, MessageHeader::FLAG_AUTH_PRIV, 3),
            new ScopedPduRequest(new GetRequest(new OidList()), 'foo'),
            null,
            new UsmSecurityParameters('foo', 1, 1, 'foo')
        ), 'maplesyrup')->getSecurityParameters()->getAuthParams()->shouldBeEqualTo(hex2bin('5ed3e0f10db58eeabbbfe7bb1efd787aa9748781f8695c3a8c2263efe9aeba676d292edebc4967dd0ed2407097a3a944'));
    }

    function it_should_authenticate_an_incoming_message_with_md5()
    {

    }

    function it_should_authenticate_an_incoming_message_with_sha1()
    {

    }
}
