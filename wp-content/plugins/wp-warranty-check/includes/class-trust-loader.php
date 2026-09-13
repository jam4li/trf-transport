<?php

namespace Trust\INC;

class TrustLoader
{
    protected $actions;
    protected $filters;

    public function __construct()
    {
        $this->actions = [];
        $this->filters = [];
    }

    public function run()
    {
        foreach ($this->actions as $_) {
            add_action($_['hook_name'], $_['callback'], $_['priority'], $_['accepted_args']);
        }
        foreach ($this->filters as $_) {
            add_action($_['hook_name'], $_['callback'], $_['priority'], $_['accepted_args']);
        }
    }

    public function add($hook_type, $hook_args)
    {
        if ($hook_type == 'action') {
            $this->actions[] = [
                'hook_name' => $hook_args['hook_name'],
                'callback' => $hook_args['callback'],
                'priority' => $hook_args['priority'],
                'accepted_args' => $hook_args['accepted_args']
            ];
        } else {
            $this->filters[] = [
                'hook_name' => $hook_args['hook_name'],
                'callback' => $hook_args['callback'],
                'priority' => $hook_args['priority'],
                'accepted_args' => $hook_args['accepted_args']
            ];
        }
    }

    public function add_action($hook_name, $callback, $priority, $accepted_args)
    {
        $this->add('action', ['hook_name' => $hook_name, 'callback' => $callback, 'priority' => $priority, 'accepted_args' => $accepted_args]);
    }

    public function add_filter($hook_name, $callback, $priority, $accepted_args)
    {
        $this->add('filter', ['hook_name' => $hook_name, 'callback' => $callback, 'priority' => $priority, 'accepted_args' => $accepted_args]);
    }
}
