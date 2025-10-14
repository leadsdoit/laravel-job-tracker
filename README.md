# Job Tracker (JT)

A Laravel package to monitor jobs by groups, track their execution status, and dispatch events on group start and
completion. Throughout the project, "**JT**" and "**jt**" are used as a short form of **Job Tracker**. All public APIs
of the package are prefixed with **JT** to avoid name conflicts.

## Installation

Use the artisan command to publish config and migration files:

```bash
php  artisan vendor:publish --provider=Ldi\\JobTracker\\JobTrackerServiceProvider
```

## Documentation

- [Description how to use](/.docs/description.md)
- [Simple example how to use](/.docs/example.md)

## License

[MIT](LICENSE)
