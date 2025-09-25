# Job Tracker (JT)

A Laravel package to monitor jobs by groups, track their execution status, and dispatch events on group start and
completion. Throughout the project, "**JT**" and "**jt**" are used as a short form of **Job Tracker**.

## Installation

Use the artisan command to publish config and migration files:

```bash
php  artisan vendor:publish --provider=AZirka\\JobTracker\\JobTrackerServiceProvider
```

## License

[MIT](LICENSE)