<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use AppBundle\Entity\Pair;
use AppBundle\Entity\Ticker;
use AppBundle\Command\Currencies;

class TradeCommand extends ContainerAwareCommand
{
    const   CAPITAL_ARG = 'capital';
    const   CURRENCY_ARG = 'pair';
    const   PAIR_SEPARATOR = '/';

    private $capital;
    private $currency;

    private $pair;

    private $first;
    private $second;
    private $third;
    private $fourth;

    private $buySpec;
    private $sellSpec;

    protected function configure()
    {
      $this
          ->setName('app:trade')
          ->setDescription('Trade currencies example.')
          ->setHelp('This command allows you to test trading currencies...')
          ->addArgument(self::CAPITAL_ARG, InputArgument::REQUIRED, 'The capital amount to invest.')
          ->addArgument(self::CURRENCY_ARG, InputArgument::OPTIONAL, 'The pair targeted to trade off. Base and quote separated by a slash. ex: ETH/EUR')
      ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $output->writeln([
          'Start trading.',
          '============',
          '',
      ]);

      $this->capital = $input->getArgument(self::CAPITAL_ARG);
      $this->currency = 0;

      $this->buySpec = $this->getContainer()->get('app.specification.should_buy');
      $this->sellSpec = $this->getContainer()->get('app.specification.should_sell');

      $provider = $this->getContainer()->get('app.provider.random_ticker');
      $this->pair = (new Pair())
        ->setId(Currencies::RANDOM.Currencies::EURO)
        ->setAltName(Currencies::RANDOM.Currencies::EURO)
        ->setBase(Currencies::RANDOM)
        ->setQuote(Currencies::EURO)
      ;
      if (null !== $input->getArgument(self::CURRENCY_ARG)) {
        $provider = $this->getContainer()->get('app.provider.currency_ticker');
        $this->pair = (new Pair())
          ->setId(str_replace(self::PAIR_SEPARATOR, '', $input->getArgument(self::CURRENCY_ARG)))
          ->setAltName(str_replace(self::PAIR_SEPARATOR, '', $input->getArgument(self::CURRENCY_ARG)))
          ->setBase(explode(self::PAIR_SEPARATOR, $input->getArgument(self::CURRENCY_ARG))[0])
          ->setQuote(explode(self::PAIR_SEPARATOR, $input->getArgument(self::CURRENCY_ARG))[1])
        ;
      }

      $tickers = $provider->get($this->pair);

      foreach ($tickers as $key => $ticker) {
          if (3 < $key) {
            $this->setTickers(
              $tickers[$key - 3],
              $tickers[$key - 2],
              $tickers[$key - 1],
              $tickers[$key]
            );
            $this->shift($ticker);
          }
      }

      $output->writeln([
        '',
        'End trading.',
        '============',
        '',
        sprintf(
          'Starting capital: %d %s',
          $input->getArgument('capital'),
          $this->pair->getQuote()
        ),
        sprintf(
          'End capital: %01.2f %s',
          $this->getEndCapital(),
          $this->pair->getQuote()
        ),
        sprintf(
          'End currency: %f %s',
          $this->currency,
          $this->pair->getBase()
        ),
        sprintf(
          'Gain: %01.2f',
          $this->getPercentage($input->getArgument('capital'))
        ).'%',
      ]);
    }

    private function getPercentage(int $base)
    {
      return round((($this->getEndCapital() - floatval($base)) / floatval($base)) * 100, 2);
    }

    private function getEndCapital()
    {
      return round($this->capital, 2);
    }

    private function setTickers(Ticker $first, Ticker $second, Ticker $third, Ticker $fourth)
    {
        $this->first = $first;
        $this->second = $second;
        $this->third = $third;
        $this->fourth = $fourth;
    }

    private function shift(Ticker $ticker)
    {
      if (
        (0 < $this->capital)
        &&
        $this->buySpec->isSatisfiedBy($this->first, $this->second, $this->third, $this->fourth)
      ) {
        $this->buy($ticker);
      }

      if (
        (0 < $this->currency)
        &&
        $this->sellSpec->isSatisfiedBy($this->first, $this->second, $this->third, $this->fourth)
      ) {
        $this->sell($ticker);
      }
    }

    private function buy(Ticker $ticker)
    {
      printf(
        "%s Bought %f %s for %s %s at %f\n",
        $ticker->getDate()->format('d/m/Y H:i:s'),
        ($this->capital / $ticker->getAsk()),
        $this->pair->getBase(),
        number_format($this->capital, 2),
        $this->pair->getQuote(),
        $ticker->getAsk()
      );

      $this->currency = $this->capital / $ticker->getAsk();
      $this->capital = 0;
    }

    private function sell(Ticker $ticker)
    {
      printf(
        "%s Sold %f %s for %s %s at %f\n\n",
        $ticker->getDate()->format('d/m/Y H:i:s'),
        $this->currency,
        $this->pair->getBase(),
        number_format($this->currency * $ticker->getBid(), 2),
        $this->pair->getQuote(),
        $ticker->getBid()
      );

      $this->capital = $this->currency * $ticker->getBid();
      $this->currency = 0;
    }
}
