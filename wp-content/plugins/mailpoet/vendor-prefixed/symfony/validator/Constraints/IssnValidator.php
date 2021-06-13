<?php
 namespace MailPoetVendor\Symfony\Component\Validator\Constraints; if (!defined('ABSPATH')) exit; use MailPoetVendor\Symfony\Component\Validator\Constraint; use MailPoetVendor\Symfony\Component\Validator\ConstraintValidator; use MailPoetVendor\Symfony\Component\Validator\Exception\UnexpectedTypeException; use MailPoetVendor\Symfony\Component\Validator\Exception\UnexpectedValueException; class IssnValidator extends \MailPoetVendor\Symfony\Component\Validator\ConstraintValidator { public function validate($value, \MailPoetVendor\Symfony\Component\Validator\Constraint $constraint) { if (!$constraint instanceof \MailPoetVendor\Symfony\Component\Validator\Constraints\Issn) { throw new \MailPoetVendor\Symfony\Component\Validator\Exception\UnexpectedTypeException($constraint, \MailPoetVendor\Symfony\Component\Validator\Constraints\Issn::class); } if (null === $value || '' === $value) { return; } if (!\is_scalar($value) && !(\is_object($value) && \method_exists($value, '__toString'))) { throw new \MailPoetVendor\Symfony\Component\Validator\Exception\UnexpectedValueException($value, 'string'); } $value = (string) $value; $canonical = $value; if (isset($canonical[4]) && '-' === $canonical[4]) { $canonical = \substr($canonical, 0, 4) . \substr($canonical, 5); } elseif ($constraint->requireHyphen) { $this->context->buildViolation($constraint->message)->setParameter('{{ value }}', $this->formatValue($value))->setCode(\MailPoetVendor\Symfony\Component\Validator\Constraints\Issn::MISSING_HYPHEN_ERROR)->addViolation(); return; } $length = \strlen($canonical); if ($length < 8) { $this->context->buildViolation($constraint->message)->setParameter('{{ value }}', $this->formatValue($value))->setCode(\MailPoetVendor\Symfony\Component\Validator\Constraints\Issn::TOO_SHORT_ERROR)->addViolation(); return; } if ($length > 8) { $this->context->buildViolation($constraint->message)->setParameter('{{ value }}', $this->formatValue($value))->setCode(\MailPoetVendor\Symfony\Component\Validator\Constraints\Issn::TOO_LONG_ERROR)->addViolation(); return; } if (!\ctype_digit(\substr($canonical, 0, 7))) { $this->context->buildViolation($constraint->message)->setParameter('{{ value }}', $this->formatValue($value))->setCode(\MailPoetVendor\Symfony\Component\Validator\Constraints\Issn::INVALID_CHARACTERS_ERROR)->addViolation(); return; } if (!\ctype_digit($canonical[7]) && 'x' !== $canonical[7] && 'X' !== $canonical[7]) { $this->context->buildViolation($constraint->message)->setParameter('{{ value }}', $this->formatValue($value))->setCode(\MailPoetVendor\Symfony\Component\Validator\Constraints\Issn::INVALID_CHARACTERS_ERROR)->addViolation(); return; } if ($constraint->caseSensitive && 'x' === $canonical[7]) { $this->context->buildViolation($constraint->message)->setParameter('{{ value }}', $this->formatValue($value))->setCode(\MailPoetVendor\Symfony\Component\Validator\Constraints\Issn::INVALID_CASE_ERROR)->addViolation(); return; } $checkSum = 'X' === $canonical[7] || 'x' === $canonical[7] ? 10 : $canonical[7]; for ($i = 0; $i < 7; ++$i) { $checkSum += (8 - $i) * (int) $canonical[$i]; } if (0 !== $checkSum % 11) { $this->context->buildViolation($constraint->message)->setParameter('{{ value }}', $this->formatValue($value))->setCode(\MailPoetVendor\Symfony\Component\Validator\Constraints\Issn::CHECKSUM_FAILED_ERROR)->addViolation(); } } } 