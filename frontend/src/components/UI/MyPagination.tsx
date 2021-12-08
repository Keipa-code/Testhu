import { Pagination } from 'react-bootstrap';
import { IPagination } from '../../types/types';
import { FC, useMemo } from 'react';
import { useHistory, useLocation } from 'react-router-dom';

interface MyPaginationProps {
  view: IPagination;
  numbers: number[];
}

const MyPagination: FC<MyPaginationProps> = ({ view, numbers }) => {
  const useQuery = () => {
    const { search } = useLocation();
    return useMemo(() => new URLSearchParams(search), [search]);
  };
  const query = useQuery();
  const router = useHistory();
  const handleClick = (pageNumber) => {
    query.set('page', String(pageNumber));
    router.push('?' + query.toString());
  };
  return (
    <div>
      <Pagination size="lg" hidden={!view}>
        <Pagination.First onClick={() => handleClick(view.first)} />
        <Pagination.Prev onClick={() => handleClick(view.previous)} />
        {numbers.map((number) => (
          <Pagination.Item onClick={() => handleClick(number)} key={number} active={+view.current === number}>
            {number}
          </Pagination.Item>
        ))}
        <Pagination.Next onClick={() => handleClick(view.next)} />
        <Pagination.Last onClick={() => handleClick(view.last)} />
      </Pagination>
    </div>
  );
};

export default MyPagination;
