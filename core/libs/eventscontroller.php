<?php
class EventsController
{
  protected $events_file = 'E:\xampp\htdocs\phx\events-list-test.txt';

  public function GETAction($request) {
    $events = $this->readEvents();
    if(isset($request->url_elements[2]) && is_numeric($request->url_elements[2])) {
      return $events[$request->url_elements[2]];
    } else {
      return $events;
    }
  }

  public function POSTAction($request) {
    // error checking and filtering input MUST go here
    $events = $this->readEvents();
    $event = array();
    $event['title'] = $request->parameters['title'];
    $event['date'] = $request->parameters['date'];
    $event['capacity'] = $request->parameters['capacity'];

    $events[] = $event;
    $this->writeEvents($events);
    $id = max(array_keys($events));
    header('HTTP/1.1 201 Created');
    header('Location: /events/'. $id); // 这里要按实际情况修改为重定向到相应的位置，下同
    return 'aaa'; // 实际不需要返回字符串，这里为了测试而已
  }

  public function PUTAction($request) {
    // error checking and filtering input MUST go here
    $events = $this->readEvents();
    $event = array();
    $event['title'] = $request->parameters['title'];
    $event['date'] = $request->parameters['date'];
    $event['capacity'] = $request->parameters['capacity'];
    $id = $request->url_elements[2];
    $events[$id] = $event;
    $this->writeEvents($events);
    header('HTTP/1.1 204 No Content');
    header('Location: /events/'. $id);
    return 'bbb';
  }

  public function DELETEAction($request) {
    $events = $this->readEvents();
    if(isset($request->url_elements[2]) && is_numeric($request->url_elements[2])) {
      unset($events[$request->url_elements[2]]);
      $this->writeEvents($events);
      header('HTTP/1.1 204 No Content');
      header('Location: /events');
    }
    return 'ccc';
  }

  protected function readEvents() {
    $events = unserialize(file_get_contents($this->events_file));
    if(empt-y($events)) { // 应该是empty,由于google syntax highlighter的bug，所以故意写错
      // 造一些数据
      $events[] = array('title' => '哈利波特与魔法石',
        'date' => date('U', mktime(0,0,0,7,1,2012)),
        'capacity' => '150');
      $events[] = array('title' => '哈利波特与凤凰社',
        'date' => date('U', mktime(0,0,0,2,14,2012)),
        'capacity' => '48');
      $this->writeEvents($events);
    }
    return $events;
  }

  protected function writeEvents($events) {
    file_put_contents($this->events_file, serialize($events));
    return true;
  }
}
