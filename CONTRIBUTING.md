# Contributing to Laravel Jetstream TALL Stack AI-Powered

Thank you for considering contributing to this starter kit! This document provides guidelines for contributing to the project.

## How to Contribute

### Reporting Bugs

If you find a bug, please create an issue on GitHub with:
- A clear, descriptive title
- Detailed steps to reproduce the issue
- Expected behavior vs actual behavior
- Your environment details (OS, PHP version, Laravel version, etc.)
- Screenshots if applicable

### Suggesting Enhancements

We welcome suggestions for new features or improvements:
- Create an issue with a clear title and detailed description
- Explain why this enhancement would be useful
- Provide examples of how it would work

### Pull Requests

1. **Fork the repository** and create your branch from `main`
2. **Make your changes** following the code style guidelines
3. **Test your changes** thoroughly
4. **Update documentation** if needed
5. **Submit a pull request** with a clear description of the changes

## Development Setup

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js and npm
- Git

### Getting Started

1. Fork and clone the repository:
```bash
git clone https://github.com/your-username/laravel-jetstream-tallstack-ai-powered.git
cd laravel-jetstream-tallstack-ai-powered
```

2. Make your changes in a new branch:
```bash
git checkout -b feature/your-feature-name
```

3. Test your changes:
```bash
# Test the installation script
./install.sh test-project

# Test with different configurations
```

## Code Style Guidelines

### PHP Code Style

- Follow PSR-12 coding standards
- Use meaningful variable and function names
- Add comments for complex logic
- Keep functions small and focused

### Shell Scripts

- Use meaningful variable names
- Add comments for complex operations
- Handle errors appropriately
- Test on different environments

### Documentation

- Keep README.md up to date
- Update QUICKSTART.md for new features
- Add inline comments for complex code
- Include examples where helpful

## Commit Message Guidelines

Write clear, concise commit messages:

```
Add feature: Brief description

Detailed explanation of what was changed and why.
Include relevant issue numbers if applicable.

Fixes #123
```

### Commit Message Format

- **Add**: New features
- **Fix**: Bug fixes
- **Update**: Updates to existing features
- **Remove**: Removing features or code
- **Refactor**: Code refactoring
- **Docs**: Documentation changes
- **Test**: Adding or updating tests

## Testing

Before submitting a pull request:

1. **Test the installation script**:
   ```bash
   ./install.sh test-project-1
   ```

2. **Test with different configurations**:
   - MySQL and SQLite
   - With and without Claude AI
   - Local and production environments

3. **Test the OTP authentication**:
   - Local environment (prefilled code)
   - Production environment (email sending)

4. **Test team features**:
   - Creating teams
   - Inviting members
   - Switching teams

## Pull Request Process

1. Update the README.md with details of changes if needed
2. Update the QUICKSTART.md if the user experience changes
3. The PR will be merged once you have approval from maintainers

## Areas for Contribution

We especially welcome contributions in these areas:

### High Priority

- **Testing**: Add automated tests for the installer
- **Error Handling**: Improve error messages and recovery
- **Documentation**: More examples and use cases
- **Internationalization**: Add translations

### Features

- **OAuth Integration**: Implement Claude OAuth login
- **Database Seeders**: Add example data seeders
- **Additional Authentication**: Support for more OTP delivery methods
- **Team Features**: Enhanced team management capabilities
- **AI Features**: More AI-powered functionality examples

### Infrastructure

- **CI/CD**: GitHub Actions workflows
- **Docker Support**: Docker compose configuration
- **Testing**: PHPUnit tests for key components
- **Code Quality**: Additional linting and analysis tools

## Code Review Process

All contributions will be reviewed by maintainers:

1. **Initial Review**: Check for code style and documentation
2. **Testing**: Verify functionality works as expected
3. **Feedback**: Provide constructive feedback if changes needed
4. **Approval**: Approve and merge when ready

## Community

- Be respectful and considerate
- Welcome newcomers and help them get started
- Provide constructive feedback
- Focus on the code, not the person

## Questions?

If you have questions about contributing:
- Open an issue with the "question" label
- Reach out to maintainers
- Check existing issues and pull requests

## Recognition

Contributors will be recognized in:
- The README.md contributors section
- Release notes for significant contributions
- Special thanks for major features or fixes

Thank you for contributing to Laravel Jetstream TALL Stack AI-Powered! 🚀

---

**Note**: By contributing, you agree that your contributions will be licensed under the MIT License.
