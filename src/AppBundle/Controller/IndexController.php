<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Pair;
use AppBundle\Entity\Ticker;
use AppBundle\Command\Currencies;
use AppBundle\Form\PairType;
use AppBundle\Specification\ShouldBuySpecification;
use AppBundle\Specification\ShouldSellSpecification;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class IndexController extends Controller
{
    private $capital;
    private $currency;
    private $lastTicker;
    private $first;
    private $second;
    private $third;
    private $fourth;

    /**
     * @Route("/", name="home")
     */
    public function homeAction(Request $request)
    {
        $form = $this->createForm(PairType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if (!isset($data['pair'])) {
              $this->redirectToRoute('home');
            }


            $provider = $this->get('app.provider.currency_ticker');
            $pair = (new Pair())
              ->setId($data['pair'].Currencies::EURO)
              ->setAltName($data['pair'].Currencies::EURO)
              ->setBase($data['pair'])
              ->setQuote(Currencies::EURO)
            ;

            $data = $provider->get($pair);
            $data = array_splice($data, (count($data) - 168), count($data));
            $data = $this->createChart($data);
            $this->get('lavacharts')->LineChart('Stocks', $data, 'stock');


            return $this->render('AppBundle:Index:pair.html.twig', [
              'lava' => $this->get('lavacharts'),
              'currency' => $this->currency,
              'capital' => $this->capital,
            ]);
        }

        return $this->render('AppBundle:Index:home.html.twig', [
          'form' => $form->createView(),
        ]);
    }

    private function createChart(array $tickers)
    {
      $data = $this->get('lavacharts')->DataTable();

      $data->addDateTimeColumn('Date')
       ->addNumberColumn($tickers[0]->getId())
       ->addRoleColumn('string', 'annotation')
      ;

      $this->capital = 100;
      $this->currency = 0;
      $this->lastTicker = null;
      $shift = '';

      foreach ($tickers as $key => $ticker) {
        if (3 < $key) {
          $this->setTickers(
            $tickers[$key - 3],
            $tickers[$key - 2],
            $tickers[$key - 1],
            $tickers[$key]
          );
          $shift = $this->shift($ticker);
        }
        $data->addRow([
          $ticker->getDate()->format('Y-m-d H:i:s'),
          $ticker->getAsk(),
          $shift,
        ]);
      }

      return $data;
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
        $this->get('app.specification.should_buy')->isSatisfiedBy($this->first, $this->second, $this->third, $this->fourth, $this->lastTicker)
      ) {
        $this->buy($ticker);
        return 'B';
      }

      if (
        (0 < $this->currency)
        &&
        $this->get('app.specification.should_sell')->isSatisfiedBy($this->first, $this->second, $this->third, $this->fourth, $this->lastTicker)
      ) {
        $this->sell($ticker);
        return 'S';
      }

      return '';
    }

    private function buy(Ticker $ticker)
    {
      $newCurrency = $this->capital / $ticker->getAsk();
      $newCurrency = $newCurrency - $this->get('app.service.percentage')->getPercentageFrom(ShouldBuySpecification::TRADER_FEE, $newCurrency);

      $this->currency = $newCurrency;
      $this->capital = 0;
      $this->lastTicker = $ticker;
    }

    private function sell(Ticker $ticker)
    {
      $newCapital = $this->currency * $ticker->getBid();
      $newCapital = $newCapital - $this->get('app.service.percentage')->getPercentageFrom(ShouldSellSpecification::TRADER_FEE, $newCapital);

      $this->capital = $newCapital;
      $this->currency = 0;
      $this->lastTicker = $ticker;
    }
}
