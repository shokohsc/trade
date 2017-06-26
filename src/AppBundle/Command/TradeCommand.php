<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use AppBundle\Entity\Pair;
use AppBundle\Entity\Ticker;
use AppBundle\Command\Currencies;
use AppBundle\Specification\ShouldBuySpecification;
use AppBundle\Specification\ShouldSellSpecification;

class TradeCommand extends ContainerAwareCommand
{
    const   CAPITAL_ARG = 'capital';
    const   CURRENCY_ARG = 'pair';
    const   PAIR_SEPARATOR = '/';

    private $capital;
    private $currency;

    private $pair;
    private $lastTicker;

    private $first;
    private $second;
    private $third;
    private $fourth;

    private $shouldBuy;
    private $shouldSell;

    private $percentageService;

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

      $this->shouldBuy = $this->getContainer()->get('app.specification.should_buy');
      $this->shouldSell = $this->getContainer()->get('app.specification.should_sell');

      $this->percentageService = $this->getContainer()->get('app.service.percentage');

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
          'Asking now at : %f',
          $ticker->getAsk()
        ),
        sprintf(
          'Gain: %01.2f',
          $this->percentageService->getGainPercentage($input->getArgument('capital'), $this->getEndCapital())
        ).'%',
      ]);
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
        $this->shouldBuy->isSatisfiedBy($this->first, $this->second, $this->third, $this->fourth, $this->lastTicker)
      ) {
        $this->buy($ticker);
      }

      if (
        (0 < $this->currency)
        &&
        $this->shouldSell->isSatisfiedBy($this->first, $this->second, $this->third, $this->fourth, $this->lastTicker)
      ) {
        $this->sell($ticker);
      }
    }

    private function buy(Ticker $ticker)
    {
      $newCurrency = $this->capital / $ticker->getAsk();
      $newCurrency = $newCurrency - $this->percentageService->getPercentageFrom(ShouldBuySpecification::TRADER_FEE, $newCurrency);
      printf(
        "%s Bought %f %s for %s %s at %f\n",
        $ticker->getDate()->format('d/m/Y H:i:s'),
        $newCurrency,
        $this->pair->getBase(),
        number_format($this->capital, 2),
        $this->pair->getQuote(),
        $ticker->getAsk()
      );

      $this->currency = $newCurrency;
      $this->capital = 0;
      $this->lastTicker = $ticker;
    }

    private function sell(Ticker $ticker)
    {
      $newCapital = $this->currency * $ticker->getBid();
      $newCapital = $newCapital - $this->percentageService->getPercentageFrom(ShouldSellSpecification::TRADER_FEE, $newCapital);
      printf(
        "%s Sold %f %s for %s %s at %f\n\n",
        $ticker->getDate()->format('d/m/Y H:i:s'),
        $this->currency,
        $this->pair->getBase(),
        number_format($newCapital, 2),
        $this->pair->getQuote(),
        $ticker->getBid()
      );

      $this->capital = $newCapital;
      $this->currency = 0;
      $this->lastTicker = $ticker;
    }
}
