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
          ->setDescription('Trade currencies.')
          ->setHelp('This command allows you to test trading currencies...')
          ->addArgument(self::CAPITAL_ARG, InputArgument::REQUIRED, 'The capital amount to invest.')
          ->addArgument(self::CURRENCY_ARG, InputArgument::OPTIONAL, 'The pair targeted to trade off. Base and quote separated by a comma. ex: ETH,EUR')
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

      $this->buySpec = $this->getContainer()->get('app.specification.shift_up_trend');
      $this->sellSpec = $this->getContainer()->get('app.specification.shift_down_trend');

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
          ->setId(str_replace(',', '', $input->getArgument(self::CURRENCY_ARG)))
          ->setAltName(str_replace(',', '', $input->getArgument(self::CURRENCY_ARG)))
          ->setBase(explode(',', $input->getArgument(self::CURRENCY_ARG))[0])
          ->setQuote(explode(',', $input->getArgument(self::CURRENCY_ARG))[1])
        ;
      }

      $tickers = $provider->get($this->pair);

      foreach ($tickers as $key => $ticker) {
          if (3 === $key) {
            $this->setTickers(
              $tickers[$key - 3],
              $tickers[$key - 2],
              $tickers[$key - 1],
              $tickers[$key]
            );
            $this->start($ticker);
          }

          if (3 < $key) {
            $this->setTickers(
              $tickers[$key - 3],
              $tickers[$key - 2],
              $tickers[$key - 1],
              $tickers[$key]
            );
            $this->shift($ticker);
          }

          if (count($tickers) - 1 === $key && 0 < $this->currency) {
            $this->end($ticker);
          }
      }

      $output->writeln([
        '',
        'End trading.',
        '============',
        '',
        'Starting capital: '.$input->getArgument('capital').' '.$this->pair->getQuote(),
        'End capital: '.round($this->capital, 2).' '.$this->pair->getQuote(),
      ]);
    }

    private function setTickers(Ticker $first, Ticker $second, Ticker $third, Ticker $fourth)
    {
        $this->first = $first;
        $this->second = $second;
        $this->third = $third;
        $this->fourth = $fourth;
    }

    private function start(Ticker $ticker)
    {
      $this->capital = (0 < $this->capital) ? $this->buy($ticker) : $this->capital;
    }

    private function end(Ticker $ticker)
    {
      $this->currency = (0 < $this->currency) ? $this->sell($ticker) : $this->currency;
    }

    private function shift(Ticker $ticker)
    {
      if ((0 < $this->capital) && $this->buySpec->isSatisfiedBy($this->first, $this->second, $this->third, $this->fourth)) {
        $this->buy($ticker);
      }
      if ((0 < $this->currency) && $this->sellSpec->isSatisfiedBy($this->first, $this->second, $this->third, $this->fourth)) {
        $this->sell($ticker);
      }
    }

    private function buy(Ticker $ticker)
    {
      printf("Bought %f %s for %f %s at %f\n", ($this->capital / $ticker->getAsk()), $this->pair->getBase(), round($this->capital, 2), $this->pair->getQuote(), $ticker->getAsk());
      $this->currency = $this->capital / $ticker->getAsk();
      $this->capital = 0;
    }

    private function sell(Ticker $ticker)
    {
      printf("Sold %f %s for %f %s at %f\n", $this->currency, $this->pair->getBase(), round($this->currency * $ticker->getBid(), 2), $this->pair->getQuote(), $ticker->getBid());
      $this->capital = $this->currency * $ticker->getBid();
      $this->currency = 0;
    }
}
