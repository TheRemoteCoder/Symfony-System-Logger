![Symfony](teaser.png)

# Symfony System Logger

TOC

- [About](#about)
- [Setup](#setup)
- [Use](#use)
- [Todo](#todo)

<br>

---

## About

Simple custom database logger.

Use cases

- Dev/Debug and monitoring purposes as alternative to wasteful CLI output and the default file based logging.
- Reduce flood of information generated e.g. by impending API calls, jobs and general service access,
  and filter the relevant ones. For instance, if there's a bug you might not need to see the same message a hundred times.

### Concepts

- Log messages can go into certain channels, like with the CLI loggers (define your own)
- Log messages are hashed and hashsum stored, so that duplicates can later be found/ filtered

## Setup

### Requirements

- Symfony `3.4` or higher
- Doctrine ORM

## Use

1. Implement in your Symfony application
2. Update Doctrine schema
3. Test via command `./bin/console test:systemlogs`
4. Check `@todo` comments – Implement features as needed

## Todo

- Check if hashsum algorithm is safe to use
- Consider implementing an additional, suggested data normalization tool
  - e.g. to scrub/ sanitize/ anonymize log or API data
