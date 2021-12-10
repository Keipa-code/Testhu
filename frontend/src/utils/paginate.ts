const computeStart = (current, total) => {
  if (total - current < 3) {
    if (total < 5) {
      return [current === 1 ? 1 : total - current > 0 ? total - current : 1, total] as const;
    }
    return [total - 4, 5] as const;
  } else {
    return [current === 1 ? 1 : current - 1, 5] as const;
  }
};

export const paginate = (current, total) => {
  const items = [];
  const [start, count] = computeStart(current, total);
  for (let number = 0; number < count; number++) {
    items.push(start + number);
  }
  return items;
};
