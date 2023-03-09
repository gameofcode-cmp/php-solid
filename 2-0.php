<?php
interface EncoderFactoryInterface {
    public function createForFormat(String $format): EncoderInterface;
}
interface EncoderInterface{
    public function encode($data): string;
}

interface ServiceLocatorInterface{
    public function has($data): string;
    public function get($data): string;
}
class EncoderFactory implements EncoderFactoryInterface {
   private $factories = [];

    public function __construct(){
        $this->addEncoderFactory('json', function () {return new JsonEncoder();});
    }
   /**
   * Register a callable that returns an instance of
   * EncoderInterface for the given format.
   *
   * @param string $format
   * @param callable $factory
   */
   public function addEncoderFactory(string $format, callable $factory): void {
       $this->factories[$format] = $factory;
   }
   public function createForFormat(string $format): EncoderInterface {
       $factory = $this->factories[$format];
       // the factory is a callable
       $encoder = $factory();
       return $encoder;
   }
}

class GenericEncoder {
    private $encoderFactory;
    public function __construct(EncoderFactoryInterface $encoderFactory) {
        $this->encoderFactory = $encoderFactory;
    }
    public function encodeToFormat($data, string $format): string
    {
        $encoder = $this->encoderFactory->createForFormat($format);
        return $encoder->encode($data);
    }
}

class JsonEncoder implements EncoderInterface
{
    public function encode($data): string
    {
        $data = $this->prepareData($data);
        return json_encode($data);
    }
    private function prepareData($data)
    {
        return $data;
    }
}

class MyCustomEncoderFactory implements EncoderFactoryInterface
{
    private $fallbackFactory;
    private $serviceLocator;
    public function __construct(
            ServiceLocatorInterface $serviceLocator,
            EncoderFactoryInterface $fallbackFactory
    ) {
        $this->serviceLocator = $serviceLocator;
        $this->fallbackFactory = $fallbackFactory;
    }
    public function createForFormat($format): EncoderInterface
    {
        if ($this->serviceLocator->has($format . '.encoder')) {
                return $this->serviceLocator->get($format . '.encoder');
        }
        return $this->fallbackFactory->createForFormat($format);
    }
}


$encoder = new GenericEncoder(new EncoderFactory());
$encoder->encodeToFormat('erewrwrer', 'json');
/*or we could use the customEncode factory. This is using a decorator to use a new encoder in a different way but
 falls back to our old factory if this is needed */


$encoderFactory = new EncoderFactory();
/*by adding the ability to add encoders outside of class we are fulfilling open for extension principle */
$encoderFactory->addEncoderFactory(
        'xml',
    function () {
        return new XmlEncoder();
    }
);
