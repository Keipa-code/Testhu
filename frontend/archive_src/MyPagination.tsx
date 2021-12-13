import { Pagination } from 'react-bootstrap';
import { IPagination } from '../src/types/types';
import { FC } from 'react';
import { observer } from 'mobx-react-lite';

interface MyPaginationProps {
  pages?: IPagination;
  changePage?: (a) => void;
}

const MyPagination: FC<MyPaginationProps> = observer(({ pages, changePage }) => {
  const handleClick = (pageNumber) => {
    changePage(pageNumber);
  };
  return (
    <div>
      <Pagination hidden={!pages.current}>
        <Pagination.First onClick={() => handleClick(pages.first)} />
        <Pagination.Prev onClick={() => handleClick(pages.previous)} disabled={Boolean(!pages.previous)} />
        {pages.numbers.map((number) => (
          <Pagination.Item onClick={() => handleClick(number)} key={number} active={+pages.current === number}>
            {number}
          </Pagination.Item>
        ))}
        <Pagination.Next onClick={() => handleClick(pages.next)} disabled={Boolean(!pages.next)} />
        <Pagination.Last onClick={() => handleClick(pages.last)} />
      </Pagination>
    </div>
  );
});

export default MyPagination;
